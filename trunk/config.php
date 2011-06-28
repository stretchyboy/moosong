<?php
  
  @include_once('localconfig.php');
  
  @define('CONST_MooSongJS', 'js/moosong.php');
  @define('CONST_OpenSongData', '../OpenSong/');
  @define('CONST_SundayCutOff', 11);
  @define('CONST_FOP_PATH',     'fop');
  @define('CONST_TEMP_PATH',    '/tmp');
  @define('CONST_SVN_AUTO',     0);
  @define('CONST_GOOGLE_EVENT_FEED',  '');
  @define('CONST_GOOGLE_EVENT_MAX_DAYS_FUTURE',  120);
  @define('CONST_NETWORK_TEST_HOST',  'www.w3.org');
  @define('CONST_DEFAULT_TIMEZONE',  'Europe/London');
  @define('CONST_YouTube_DL',  './youtube-dl');
  @define('CONST_FileGroup',  false);
  @define('CONST_SiteTitle',  "MooSong");
  
  @define('CONST_OpenSongSets', CONST_OpenSongData.'Sets/');
  @define('CONST_OpenSongSongs', CONST_OpenSongData.'Songs/');
  @define('CONST_OpenSongVideos', CONST_OpenSongData.'Videos/');
  @define('CONST_OpenSongImages', CONST_OpenSongData.'Images/');
  @define('CONST_OpenSongPresentations', CONST_OpenSongData.'Presentations/');
  
  
  
  @define('CONST_VLC_BIN', '"C:\OSTools\VideoLan\VLC\VLC.EXE"');
  
  @define('CONST_VLC_DVD_CLIP_BY_TIME', CONST_VLC_BIN.' "dvdsimple://D:@<<dvdtitlenumber>>" ":start-time=<<start-time>>" ":stop-time=<<stop-time>>"');
  @define('CONST_SCRIPT_DVD_CLIP_BY_TIME', '<<dvdtitle>>:<<dvdtitlenumber>>:<<start-time>>:<<stop-time>>');
  
  @define('CONST_VLC_DVD_CLIP_BY_CHAPTER', CONST_VLC_BIN.' "dvdsimple://D:@<<dvdtitlenumber>>:<<start-chapter>>-<<dvdtitlenumber>>:<<stop-chapter>>"');  
  @define('CONST_SCRIPT_DVD_CLIP_BY_CHAPTER', '<<dvdtitle>>:<<dvdtitlenumber>>:<<start-chapter>>-<<dvdtitlenumber>>:<<stop-chapter>>');

  @define('CONST_SCRIPT_DVD_CLIP_EXT','.bat');

