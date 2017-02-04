using System;

namespace AVChat
{
	 public partial class getRecordedVideosInfo : System.Web.UI.Page
    {
		protected void Page_Load(object sender, EventArgs e)
        {
				string streamName = Server.UrlDecode(this.Request.Params["streamName"]);// the name of the recorded videostream
				string siteId = Server.UrlDecode(this.Request.Params["siteId"]);//the siteId of the user that made the recorded videostream
				string username = Server.UrlDecode(this.Request.Params["username"]);//the username of the user that made the recorded videostream
				               
				string result = "$result="+ip+" "+siteId+" "+username;

				Response.Write(result); 
		}
	}
}


