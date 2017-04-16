function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}


// This is for the Polls popup window 
function ShowChart(pid)
{
	mywin = window.open('','popuppoll','scrollbars=yes,width=510,height=400');
	mywin.location.href = "/Polls/PollResults.cfm?PID=" + pid ;
}