{"version":3,"file":"pull.min.js","sources":["pull.js"],"names":["window","BX","console","log","PULL","_revision","_updateStateVeryFastCount","_updateStateFastCount","_updateStateStep","_updateStateTimeout","_updateStateStatusTimeout","_updateStateSend","_pullTryAfterBxLink","_pullTryConnect","_pullPath","_pullMethod","_pullWithHeaders","_pullTimeConfig","_pullTimeConfigShared","_pullTimeConst","Date","toUTCString","_pullTime","_pullTag","_pullTimeout","_pullMid","_watchTag","_watchTimeout","_channelID","_channelClearReason","_channelClear","_channelLastID","_channelStack","_WS","_wsPath","_wsSupport","_wsConnected","_wsTryReconnect","_wsError1006Count","_mobileMode","_lsSupport","_escStatus","_sendAjaxTry","_confirm","_beforeUnload","_pathToAjax","_onBeforeUnload","proxy","close","returnPrivateVar","tryConnectDelay","this","start","params","MOBILE","LOCAL_STORAGE","HEADERS","localStorage","get","WEBSOCKET","bind","tryConnect","updateState","browser","IsFirefox","event","keyCode","supportWebSocket","PATH_COMMAND","setAjaxPath","CHANNEL_ID","getModernPath","PATH_WS","METHOD","CHANNEL_DT","toString","split","parseInt","message","LAST_ID","SupportLocalStorage","addCustomEvent","storageSet","set","PATH","TIME_LAST_GET","TIME_LAST_GET_SHARED","error","delegate","desktop","initBeforeUnload","onCustomEvent","expireConfig","init","updateWatch","getNowDate","today","currentDate","getFullYear","getMonth","getDate","Math","round","getDateDiff","timestamp","userTzOffset","localTimestamp","incomingTimestamp","setTryAfterBxLink","result","unbind","setTimeout","setPrivateVar","clearTimeout","floor","random","getChannelID","code","withoutCache","send","ajax","url","toUpperCase","method","skipAuthCheck","dataType","lsId","lsTimeout","timeout","data","PULL_GET_CHANNEL","SITE_ID","CACHE","PULL_AJAX_CALL","sessid","bitrix_sessid","onsuccess","ERROR","REVISION","checkRevision","BITRIX_SESSID","tryConnectTimeout","text","JSON","stringify","onfailure","force","length","connectWebSocket","connectPull","wsPath","replace","location","hostname","_wsServer","WebSocket","e","onopen","onclose","reason","parse","neverConnect","sendConnectRequest","wasClean","http_status","onmessage","messageCount","dataArray","match","i","substring","parseJSON","id","channel","time","executeMessages","MESSAGE","SERVER_TIME","SERVER_TIME_WEB","tag","mid","onerror","headersForRequest","name","value","pullPath","_ajax","skipBxHeader","headers","PULL_UPDATE_STATE","CHANNEL_LAST_ID","TAG","MID","TIME","setUpdateStateStep","status","expires","getResponseHeader","lastMessageId","extendWatch","clearWatch","arWatchTag","hasOwnProperty","push","pathname","PULL_UPDATE_WATCH","WATCH","pull","module_id","toLowerCase","command","updateChannelID","CHANNEL_DIE","PREV_CHANNEL_ID","tryConnectSet","setUpdateStateStepCount","debug","step","veryFastCount","fastCount","key","indexOf","channelID","lastId","sendAjaxTry","pullTryConnect","getPullServerStatus","capturePullEvent","getDebugInfo","textWT","clearChannelId","IsChrome","IsOpera","IsSafari","navigator","userAgent","substr","appVersion","DetectIeVersion","PATH_MOD","IsIE","getRevision","getDebugInfoArray","connected","websocket","path","revision","openConfirm","v","eval","va","ve","buttons","modal","destroy","PopupWindowButton","className","events","click","popupWindow","PreventDefault","PopupWindow","zIndex","autoHide","closeByEsc","overlay","onPopupClose","onPopupDestroy","content","create","props","html","show","popupContainer","preventDefault","contentContainer","element","closeConfirm","stopPropagation","cancelBubble"],"mappings":"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"}