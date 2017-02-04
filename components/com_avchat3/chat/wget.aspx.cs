using System;
using System.IO;
using System.Net;

namespace AVChat
{
    public partial class wget : System.Web.UI.Page
    {
		string rsp = string.Empty;
		  
		protected void Page_Load(object sender, EventArgs e){
			Response.AddHeader("Content-Type", "image/"+this.Request.Params["type"]);	
			System.Net.WebClient wc = new System.Net.WebClient();

            bool result = Uri.TryCreate(Request.QueryString["url"], UriKind.RelativeOrAbsolute, out uriResult) && uriResult.Scheme == Uri.UriSchemeHttp;
            if (result)
            {
                byte[] rsp = wc.DownloadData(Request.QueryString["url"]);
                Response.BinaryWrite(rsp);
            }
			
		}	
	}
}