var save_timer = null;
function line_no_from_elem(el)
{
    var lineno;
    while (typeof(lineno) == "undefined" && el)
    {
        lineno = el.attr('lineno');
        el = el.parent();
    }
    return lineno;
}
function get_orig_text(lineno)
{
    return jQuery('#tr_' + lineno + ' > td:first-child > div:first-child').text();
}
function get_key(lineno)
{
    return jQuery('#tr_' + lineno).attr('key');
}
function get_form_input(lineno)
{
    var input = jQuery('#tr_' + lineno + ' input');
    if (input.length < 1)
        input = jQuery('#tr_' + lineno + ' textarea');
    return input;
}
function get_approve_button(lineno)
{
    return jQuery('#tr_' + lineno + ' button');
}
jQuery(document).ready(function () {
    jQuery('.form_header').each(function () {
        var set = jQuery(this).attr('set');
        var img = "<span class='toggle_set' id='set_toggle_" + set + "'>&minus;</span>";
        jQuery(this).prepend(img);
    });
    update_set_totals();
    jQuery('.existing_table input, .existing_table textarea').each(function () {
        var elem = jQuery(this);
        // Save current value of element
        elem.data('oldVal', elem.val());
        // Look for changes in the value
        elem.bind("propertychange keyup input paste", function (event) {
            // If value has changed...
            if (elem.data('oldVal') != elem.val()) {
                // Updated stored value
                elem.data('oldVal', elem.val());
                tm_input_changed(elem);
            }
        });
    });
    jQuery('#filter_input').each(function () {
        var elem = jQuery(this);
        // Save current value of element
        elem.data('oldVal', elem.val());
        // Look for changes in the value
        elem.bind("propertychange keyup input paste", function (event) {
            // If value has changed...
            if (elem.data('oldVal') != elem.val()) {
                // Updated stored value
                elem.data('oldVal', elem.val());
                tm_filter(elem);
            }
        });
    });
    jQuery('#toolbar').parent().prepend(jQuery('#trans_filter'));
    jQuery('#trans_filter').show();
    jQuery('#filter_clear').click(function () {
        jQuery('#filter_input').val("");
        tm_filter("");
    });
    jQuery('.copy_orig').click(function (ev) {
        var lineno = line_no_from_elem(jQuery(this));
        var orig = get_orig_text(lineno);
        var elem = get_form_input(lineno);
        elem.val(orig);
        elem.parent().parent().removeClass('success');
        elem.parent().parent().removeClass('warning');
        elem.parent().parent().addClass('error');
        elem.attr('isdone', "0");
        elem.parent().find('button').show();
        elem.parent().addClass('input-append');
        update_set_totals();
    });
    jQuery('.auto_tran').click(function (ev) {
        ev.preventDefault();
        tm_auto_phrase(line_no_from_elem(jQuery(this)));
    });
    jQuery('.input_cont > div > button').click(function (ev) {
        ev.preventDefault();
        var elem = jQuery(this).parent().find('input');
        if (elem.length > 0) {
            tm_input_changed(elem);
        }
        elem = jQuery(this).parent().find('textarea');
        if (elem.length > 0) {
            tm_input_changed(elem);
        } 
    });
    //jQuery('textarea').autoresize();
    tm_reset_save_timer();
    jQuery('.add_new_entry_button').click(function (ev) {
        ev.preventDefault();
        tm_add_new();
    });
});
function tm_reset_save_timer() {
    if (save_timer)
        clearTimeout(save_timer);
    save_timer = setTimeout("tm_save_notify()", 10 * 60 * 1000);
}
function tm_save_notify() {
    jQuery('#notice_savewarning').show();
}
function tm_save_warning_act(save) {
    if (save) {
        tm_save();
    }
    jQuery('#notice_savewarning').hide();
    tm_reset_save_timer();
}
function tm_toggle_set(setid) {
    var table = jQuery('#form_table_' + setid);
    if (table.is(":visible")) {
        hide_set(setid);
    } else {
        show_set(setid);
    }
}
function show_set(setid)
{
    var table = jQuery('#form_table_' + setid);
    table.show();
    if (jQuery('#hide_headers').length > 0)
    {    
        var header = jQuery('.form_section_' + setid);
        header.show();
    }
    jQuery('#set_toggle_' + setid).html('&minus;');
}
function hide_set(setid)
{
    var table = jQuery('#form_table_' + setid);
    table.hide();
    if (jQuery('#hide_headers').length > 0)
    {    
        var header = jQuery('.form_section_' + setid);
        header.hide();
    }
    jQuery('#set_toggle_' + setid).html('&plus;');
}
function tm_input_changed(elem) {
    if (elem.attr('isdone') == "1")
        return;
    elem.parent().parent().removeClass('warning');
    elem.parent().parent().removeClass('error');
    elem.parent().parent().addClass('success');
    elem.attr('isdone', "1");
    elem.parent().find('button').hide();
    elem.parent().removeClass('input-append');
    update_set_totals();
}
function update_set_totals() {
    jQuery('.form_header').each(function () {
        var set = jQuery(this).attr('set');
        var done = 0;
        var missing = 0;
        jQuery('#form_table_' + set).find('.input_cont').each(function () {
            if (jQuery(this).hasClass('success')) {
                done++;
            }
            if (jQuery(this).hasClass('error') || jQuery(this).hasClass('warning')) {
                missing++;
            }
        });
        jQuery('#form_header_info_' + set).html("<div class='alert alert-success'>" + done + "</div><div class='alert alert-error'>" + missing + "</div>");
    });
}
function tm_show_sections(type) {
    jQuery('.form_header').each(function () {
        var set = jQuery(this).attr('set');
        //var done = parseInt(jQuery('form_header_info_' + set + ' .alert-success').text());
        var missing = parseInt(jQuery('#form_header_info_' + set + ' .alert-error').text());
        if (type == "all") {
            show_set(set);
        } else if (type == "none") {
            hide_set(set);
        } else if (type == "complete") {
            if (missing == 0) {
                show_set(set);
            } else {
                hide_set(set);
            }
        } else if (type == "incomplete") {
            if (missing > 0) {
                show_set(set);
            } else {
                hide_set(set);
            } 
        }
    });
}
function show_line(line) {
    jQuery('#tr_' + line).show();
}
function hide_line(line) {
    jQuery('#tr_' + line).hide();
}
var cur_types = "all";
function tm_show_lines(type) {
    cur_types = type;
    tm_filter(jQuery('#filter_input').val());
}
function tm_filter(text) {
    if (text == "") {
        jQuery('#filter_clear').hide();
        jQuery('#filter_div').removeClass('input-append');
    } else {
        jQuery('#filter_clear').show();
        jQuery('#filter_div').addClass('input-append');
    }
    var search = jQuery.trim(jQuery('#filter_input').val());
    var words = search.split(" ");
    jQuery('.existing_table input, .existing_table textarea').each(function () {
        var done = true;
        if (jQuery(this).parent().parent().hasClass('error') || jQuery(this).parent().parent().hasClass('warning'))
            done = false;
        var line = line_no_from_elem(jQuery(this));
        var show = true;
        if (cur_types == "complete") {
            if (!done) {
                show = false;
            }
        } else if (cur_types == "incomplete") {
            if (done) {
                show = false;
            }
        }
        if (search.length > 0) {
            var origtext = get_orig_text(line);
            var key = get_key(line);
            var translated = jQuery(this).val();
            for (i = 0; i < words.length; i++ ) {
                var word = words[i];
                if (!tm_word_in(word, origtext) &&
                    !tm_word_in(word, origtext) &&
                    !tm_word_in(word, origtext)) {
                    show = false;
                }
            }
        }
        if (show) {
            show_line(line);
        } else {
            hide_line(line);
        }
    });
    jQuery('#item-form .extra_input').each(function () {
        var id = jQuery(this).attr('id').split("_")[2];
        var value = jQuery(this).val();
        var key = jQuery('#extra_key_' + id).val();
        var show = true;
        if (search.length > 0) {
            for (i = 0; i < words.length; i++) {
                var word = words[i];
                if (!tm_word_in(word, key) &&
                    !tm_word_in(word, value)) {
                    show = false;
                }
            }
        }
        if (show) {
            jQuery('#extra_' + id).show();
        } else {
            jQuery('#extra_' + id).hide();
        }
    });
}
function tm_word_in(find, line) {
    find = find.toString().toLowerCase();
    line = line.toString().toLowerCase();
    if (line.indexOf(find) === -1)
        return false;
    return true;
}
function tm_save(close) {
    jQuery('#notice_saveing').show();
    tm_reset_save_timer();
    // need to json the form
    var data = { option: "com_fsj_transman", task: "file.apply" };
    data.file = jQuery('#form_file').val();
    data.header = jQuery('#input_header').val();
    data.strings = {};
    jQuery('.existing_table input, .existing_table textarea').each(function () {
        var line = line_no_from_elem(jQuery(this));
        var key = get_key(line);
        var translated = jQuery(this).val();
        var done = true;
        if (jQuery(this).parent().parent().hasClass('error') || jQuery(this).parent().parent().hasClass('warning'))
            done = false;
        if (done)
            data.strings[key] = translated;
    });
    var extra_id = parseInt(jQuery('#max_extra').text());
    for (i = 0; i < extra_id; i++) {
        var key = jQuery('#extra_key_' + i).val();
        var value = jQuery('#extra_input_' + i).val();
        if (key && key.length > 0 &&
            value && value.length > 0)
            data.strings[key] = value;
    }
    data.strings = JSON.stringify(data.strings);
    jQuery.post("index.php", data)
    .done(function (result) {
        jQuery('#notice_saveing').hide();
        try {
            var parsed = jQuery.parseJSON(result);
        } catch (e) {
            // error parsing json, so display a warning about this
            tm_show_error("Invalid response recieved from server<br />" + result);
            return;
        }
        if (parsed.result == "error") {
            tm_show_error(parsed.error);
            return;
        }
        if (close) {
            var url = jQuery('#cancel_url').text();
            window.location.href = url;
        } else {
            jQuery('#notice_saved').show();
            setTimeout("jQuery('#notice_saved').hide();", 4000);
        }
    });
    return false;
}
function tm_show_error(error) {
    jQuery('#save_error p').html(error);
    jQuery('#save_error').show();
}
function tm_close_saved() {
    jQuery('#notice_saved').hide();
}
function tm_remove_extra(lineno) {
    jQuery('#extra_' + lineno).remove();
}
function tm_add_new() {
    var table = jQuery('.new_table');
    var extra_id = parseInt(jQuery('#max_extra').text());
    jQuery('#max_extra').text(extra_id + 1);
    var html = "<tr id='extra_" + extra_id + "'>\n";
    html += "       <td width='50%' valign='top'>\n";
	html += "			<div class='additional input_div'>\n";
	html += "				<div class='remove_extra'>\n";
	html += "					<button class='btn btn-mini' \n";
	html += "						onclick='return tm_remove_extra(" + extra_id + ")'>\n";
	html += "						<i class='icon-cancel'></i>\n";
	html += "					</button>\n";
	html += "				</div>\n";
	html += "				<input \n";
	html += "					type='text' \n";
	html += "					id='extra_key_" + extra_id + "' \n";
	html += "					value='' \n";
    html += "                   onchange='tm_check_key(this)' \n";
	html += "					/> \n";
	html += "			</div>\n";
	html += "		</td>\n";
	html += "		<td width='50%' valign='top'>\n";
	html += "			<div class='input_cont control-group input_div'>\n";
	html += "				<input \n";
	html += "					type='text' \n";
	html += "					id='extra_input_" + extra_id + "' \n";
	html += "					class='extra_input' \n";
	html += "					value=''\n";
	html += "				/>\n";
	html += "			</div>&nbsp;\n";
	html += "		</td>\n";
	html += "	</tr>\n";
	table.append(html);
}
function tm_check_key(elem) {
    var value = jQuery(elem).val();
    value = value.toUpperCase();
    value = jQuery.trim(value);
    value = value.replace(/[ ]/g, '_');
    value = value.replace(/[^A-Z0-9_]/g, '');
    jQuery(elem).val(value);
}
function tm_approve_all() {
    if (!confirm(jQuery('#approve_all_confirm').text()))
        return;
    jQuery('.existing_table input, .existing_table textarea').each(function () {
        var lineno = line_no_from_elem(jQuery(this));
        if (jQuery('#tr_' + lineno + ' .input_cont').hasClass('warning') ||
            jQuery('#tr_' + lineno + ' .input_cont').hasClass('error'))
            tm_input_changed(jQuery(this));
    });
    update_set_totals();
}
function tm_approve_set(set_id) {
    jQuery('#form_table_' + set_id + ' input, #form_table_' + set_id + ' textarea').each(function () {
        var lineno = line_no_from_elem(jQuery(this));
        if (jQuery('#tr_' + lineno + ' .input_cont').hasClass('warning') ||
            jQuery('#tr_' + lineno + ' .input_cont').hasClass('error'))
            tm_input_changed(jQuery(this));
    });
    update_set_totals();
}
function tm_auto_all()
{
    alert("Auto-Translation is only available in Translation Manager Pro");
}
function tm_auto_set()
{
    alert("Auto-Translation is only available in Translation Manager Pro");
}
function tm_auto_phrase()
{
    alert("Auto-Translation is only available in Translation Manager Pro");
}
