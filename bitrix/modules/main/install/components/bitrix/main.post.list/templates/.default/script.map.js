{"version":3,"file":"script.min.js","sources":["script.js"],"names":["window","safeEditing","safeEditingCurrentObj","quoteData","FCList","params","add","this","CID","ENTITY_XML_ID","container","nav","mid","order","status","msg","innerHTML","pullNewRecords","rights","BX","addCustomEvent","delegate","id","data","check","bind","proxy","e","PreventDefault","get","ajax","url","method","dataType","AJAX_POST","MODE","sessid","bitrix_sessid","sign","PATH_TO_USER","AVATAR_SIZE","NAME_TEMPLATE","SHOW_LOGIN","ENTITY_ID","obj","quote","popup","hide","location","hash","parseInt","replace","checkHash","show","setTimeout","quoteShow","res","nodes","findChildren","parentNode","tagName","className","push","ii","length","dnd","bxDndIsBound","reply","prototype","DoNothing","quoteCheck","text","range","author","getSelection","toString","document","selection","createRange","parent","endParent","findParent","focusNode","startParent","anchorNode","hasAttribute","node","getAttribute","name","MPLQuote","closeByEsc","autoHide","autoHideTimeout","events","click","onCustomEvent","classEvents","onQuoteHide","display","startHeight","fxStart","fxFinish","time","el","offsetHeight","style","overflow","easing","duration","start","height","opacity","finish","transition","makeEaseOut","transitions","quart","step","state","maxHeight","complete","cssText","animate","adjust","attrs","bx-visibility-status","html","message","send","addClass","prepareData","FILTER",">ID","<ID","util","htmlspecialcharsback","indexOf","substr","onsuccess","build","onfailure","wait","removeClass","create","ob","processHTML","HTML","firstChild","href","cnt","func","childNodes","processScripts","SCRIPT","defer","type","isElementNode","undefined","edit","animation","join","fcParseTemplate","messageFields","DATA_TIME_FORMAT","RIGHTS","results","insertBefore","remove","acts","needToCheck","lastChild","addCheckPreviousNodes","results2","res2","pop","setAttribute","appendChild","curPos","pos","scroll","GetWindowScrollPos","size","GetWindowInnerSize","scrollHeight","scrollTop","top","innerHeight","scrollTo","cubic","pullNewAuthor","authorId","authorName","authorAvatar","pullNewRecord","ajaxSend","ID","node1","findChild","hasClass","removeAttribute","act","eval","isFunction","fcShowWait","NOREDIRECT","fcCloseWait","busy","getQuoteData","source","el2","start1","finish1","LazyLoad","showImages","split","lastWaitElement","disabled","waiter_parent","is_relative","bxwaiter","body","props","position","removeChild","panels","onclick","it","proxy_context","pos2","urlView","width","pos3","Math","max","bx-height","children","value","protocol","host","select","fx","callback","popupWindow","close","confirm","PopupMenu","offsetLeft","offsetTop","lightShadow","angle","offset","onPopupClose","destroy","Data","replacement","FULL_ID","NEW","APPROVED","DATE","TEXT","CLASSNAME","VIEW_URL","VIEW_SHOW","EDIT_URL","EDIT_SHOW","MODERATE_URL","MODERATE_SHOW","DELETE_URL","DELETE_SHOW","BEFORE_HEADER","BEFORE_ACTIONS","AFTER_ACTIONS","AFTER_HEADER","BEFORE","AFTER","AUTHOR_ID","AUTHOR_AVATAR_IS","AUTHOR_AVATAR","AUTHOR_URL","AUTHOR_NAME","BEFORE_RECORD","AFTER_RECORD","AUTHOR_EXTRANET_STYLE","txt","mplReplaceUserPath","date","format","ij","DATA","Date","getTime","markReadComments","commentsReadToCounter","i","eventName","hasOwnProperty","createElement","zIndex","PopupWindow","getOption","left","getPosition","y","x","isCloseByEscBinded","_onKeyUp","isAutoHideBinded","cancelBubble","autoHideTimeoutInt","autoHideTimeoutBinded","event","isShown","getEventButton","MSLEFT","unbind","clearTimeout","_hide","nodePos","pageX","doc","documentElement","clientX","scrollLeft","clientLeft","clientY","clientTop","pageY","stopPropagation","keyCode","mplCheckForQuote","author_id","all","tmp","RegExp"],"mappings":"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"}