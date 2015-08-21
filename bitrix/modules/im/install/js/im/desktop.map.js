{"version":3,"file":"desktop.min.js","sources":["desktop.js"],"names":["window","BX","desktop","params","this","apiReady","clientVersion","autorun","focusTimeout","lastSetIcon","showNotifyId","popupConfirm","htmlWrapperHead","tryCheckConnect","topmostWindow","topmostWindowTimeout","content","contentFullWindow","contentMenu","contentAvatar","contentTab","contentTabContent","currentTab","currentTabTarget","lastTab","lastTabTarget","path","mainUserOptions","pathToAjax","tabItems","tabRedrawTimeout","userInfo","id","name","gender","avatar","profile","syncStatus","syncCounter","width","height","minWidth","minHeight","timeoutDelayOfLogout","addCustomEvent","delegate","terminate","reason","onCustomEventForTab","TAB_CP","logout","bind","e","keyCode","ctrlKey","metaKey","shiftKey","BXIM","setLocalConfig","console","log","windowReload","openDeveloperTools","openLogsFolder","prototype","init","offsetWidth","style","offsetHeight","create","attrs","document","body","insertBefore","firstChild","ready","enableInVersion","PULL","tryConnectSet","notSupported","setWindowResizable","setWindowMinSize","Width","Height","message","replace","getApiVersion","browser","IsMac","head","type","html","addClass","addTab","title","order","target","events","open","newRevision","oldRevision","closeConfirm","location","reload","error","code","setIconStatus","login","setPrivateVar","updateState","sendErrorCode","textError","userEmail","messenger","connectionStatus","userOptions","setAjaxPath","status","settings","preventShutdown","adjustSize","tabId","changeTab","onCustomEvent","setTimeout","finalizeTrayMenu","setWindowSize","setWindowTitle","updateContent","props","className","children","click","checkUpdate","innerHTML","appendChild","getCurrentUrl","protocol","hostname","port","diskReady","callback","success","sessid","bitrix_sessid","loginSuccessCallback","BXDesktopSystem","Login","showLoginForm","Logout","skipCheck","href","getContextWindow","getNetworkAuthorizeStatus","TAB_B24NET","clearTimeout","ajax","url","method","dataType","timeout","data","IM_DESKTOP_LOGOUT","onsuccess","Shutdown","onfailure","openBrowser","ExecuteCommand","NotifyNoUpdates","ShowNotifications","browse","full","GetProperty","join","version","parseInt","eventName","eventHandler","addEventListener","arEventParams","i","detail","push","apply","windowTarget","arguments","length","objEventParams","mainWindow","opener","top","BXWindows","BXDesktopWindow","DispatchCustomEvent","findWindow","tabIdTarget","windowIsFocused","SetIconStatus","setIconBadge","count","important","SetIconBadge","setIconTooltip","iconTitle","enabled","SetProperty","setWindowClosable","flashIcon","voiced","FlashIcon","checkInternetConnection","successCallback","failureCallback","tryCount","tryName","Date","skipAuthCheck","skipBxHeader","getWorkArea","coordinates","GetWorkArea","left","right","bottom","showNotification","notifyId","js","getHtmlPage","innerWidth","innerHeight","Math","max","resize","syncPause","immediate","BXFileStorage","SyncPause","getSyncPauseStatus","windowCommand","command","setActiveWindow","trace","openTopmostWindow","bodyClass","closeTopmostWindow","jsContent","outerHTML","isDomNode","OpenDeveloperTools","OpenLogsFolder","autorunStatus","value","diskAttachStatus","BitrixDisk","clipboardSelected","element","resultText","tagName","selectionStart","selectionEnd","substring","getSelection","toString","range","getRangeAt","cloneContents","div","createElement","util","htmlspecialcharsback","split","clipboardCopy","cut","execCommand","clipboardTextArea","position","opacity","focus","text","textNew","remove","clipboardCut","clipboardPaste","clipboardDelete","clipboardUndo","clipboardRedo","selectAll","getLocalConfig","def","result","QuerySettings","JSON","parse","stringify","StoreSettings","removeLocalConfig","openConfirm","buttons","modal","destroy","PopupWindowButton","popupWindow","close","PreventDefault","PopupWindow","zIndex","autoHide","closeByEsc","overlay","onPopupClose","onPopupDestroy","show","popupContainer","contentContainer","filename","Log","createWindow","GetWindow","getWindowTitle","trim","setWindowPosition","addTrayMenuItem","AddTrayMenuItem","EndTrayMenuItem","PreventShutdown","diskReportStorageNotification","ReportStorageNotification","diskOpenFolder","OpenFolder","addSeparator","drawTabs","hide","badge","initContent","force","drawAppearance","arTabs","objectSort","drawTab","updateTabBadge","data-id","bindDelegate","event","proxy_context","getAttribute","fireEvent","removeClass","closeTab","getCurrentTab","setTabBadge","counter","findChild","SetTabBadge","setTabContent","getCurrentTabTarget","isActiveWindow","IsActiveTab","getActiveWindow","ActiveTab","windowId","SetActiveTabI","SetActiveTab","setUserInfo","onclick","src","updateUserInfo","getUserInfo"],"mappings":"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"}