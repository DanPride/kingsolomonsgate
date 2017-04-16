function retarget( path, title ) {
      sc_clear_video_events () ;
      }
function video_event( event_name, value ) {
      sc_record_video_event( event_name, value ) ;
      }
function video_evar( value ){
      sc_video_evar( value );
      }
function setEvent( event_name, value ){
     sc_setEvent( event_name, value );
     }


function sc_clear_video_events()
     {
         s.prop14 = ''
         s.prop30 = ''
         s.prop31 = ''
         s.prop32 = ''
         s.prop33 = ''
         s.prop34 = ''
         s.prop35 = ''
         s.prop36 = ''
         s.prop37 = ''
         s.prop38 = ''
         s.prop39 = ''
         s.prop40 = ''
         s.prop41 = ''
         s.prop42 = ''
     }
     function sc_record_video_event( event_name, value )
     {
        
         if( event_name == 'stopped' )
         {
             s.prop31 = event_name ;
             s.prop32 = value ;
         }
        else if ( event_name == 'video_name' )
        {
            s.prop30 = value;
            s.eVar4 = value;
        }
         else if ( event_name == 'content_type' )
        {
            s.prop14 = 'Video'
        }
         else if ( event_name == 'preroll_stopped' )
         {
             s.prop33 = event_name ;
             s.prop34 = value ;
         }
         else if( event_name == 'preroll_completed' )
         {
             s.prop35 = event_name ;
             s.prop36 = value ;
         }
         else if( event_name == 'mail_friend' )
         {
             s.prop37 = value ;
         }
         else if( event_name == 'prerollrequest' )
         {
             s.prop41 = value ;
         }
         else if( event_name == 'videoplayer' )
         {
            s.prop38 = value;
            s.eVar3 = value;
         }
         else if( event_name == 'prerollname' )
         {
            s.prop39 = value;
         }
         else if( event_name == 'videototaltime' )
         {
            s.prop40 = value;
         }
         else if( event_name == 'video_complete')
         {
            s.prop42 = value;
         }
     
     }  
     
     
     function sc_video_evar( value ){
		s.eVar6 = value;
	}

    function sc_setEvent( event_name, value ){
         eval(event_name = value);
     }

	function mediaOpen(mediaName,mediaLength,mediaPlayerName){
    	s.Media.open(mediaName,mediaLength,mediaPlayerName);
    }

    function mediaPlay(mediaName,mediaOffset){
        s.Media.play(mediaName,mediaOffset);
     }

    function mediaStop(mediaName,mediaOffset){
       s.Media.stop(mediaName,mediaOffset);
    }

   function mediaClose(mediaName){
       s.Media.close(mediaName); 
   }
