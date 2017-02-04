/*this file is called by AVChat every second after the PPV timer has started
ppvAmountLeft: the amount of time/money/credits left for a particular user.
ppvInitialAmount: the initial amount of time/money/credits that a user had.
ppvRatio: the rate at which the ppvAmountLeft is decreased.
userSiteId: the siteId value as sent by avc_settings.xxx
sessionTimeStamp: the timestamp from the last login*/

using System;


namespace AVChat
{
	 public partial class PPV : System.Web.UI.Page
    {
		protected void Page_Load(object sender, EventArgs e)
        {
            string ppvAmountLeft = this.Request.Params["ppvAmountLeft"];
            string ppvInitialAmount = this.Request.Params["ppvInitialAmount"];
            string ppvRatio = this.Request.Params["ppvRatio"];
            string userSiteId = this.Request.Params["userSiteId"];
            string sessionTimeStamp = this.Request.Params["sessionTimeStamp"];
               		   
		}
	}
}


