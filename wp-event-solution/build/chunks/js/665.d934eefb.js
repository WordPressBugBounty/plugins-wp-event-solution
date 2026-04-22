"use strict";(globalThis.webpackChunkwp_event_solution=globalThis.webpackChunkwp_event_solution||[]).push([[665],{311883(e,t,n){n.d(t,{A:()=>o});var i=n(951609),a=n(406836);const o=({height:e=22,width:t=22})=>(0,a.iconCreator)(()=>(({height:e,width:t})=>(0,i.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",fill:"none",viewBox:"0 0 20 20"},(0,i.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeLinejoin:"round",strokeWidth:"1.5",d:"M6.25 4.121h7.083c.69 0 1.25.56 1.25 1.25v1.25M12.5 10.788h-5M10 14.121H7.5"}),(0,i.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeWidth:"1.5",d:"M15.414 1.667H5.256c-.414 0-.837.06-1.172.306-1.062.78-1.88 2.517-.228 4.086.464.44 1.112.6 1.75.6h9.63c.662 0 1.847.095 1.847 2.114v6.211a3.34 3.34 0 0 1-3.33 3.35H6.226c-1.836 0-3.172-1.298-3.277-3.274L2.922 4.304"})))({height:e,width:t}))},957584(e,t,n){n(951609),n(406836)},808358(e,t,n){n.d(t,{A:()=>c});var i=n(951609),a=n(527723),o=n(354725),l=n(658950),s=n(922451),r=n(327154);function c({children:e,...t}){return(0,i.createElement)(s.u,{type:l.rd.type,size:l.rd.size,variantstyle:l.rd.style,onClick:()=>window.open(r.PRO_UPGRADE_LINK,"_blank"),...t},(0,i.createElement)(o.DiamondProIcon,null),e||(0,a.__)("Get Eventin Pro","eventin"))}},217026(e,t,n){n.d(t,{A:()=>r});var i=n(951609),a=n(377278),o=n(16370),l=n(747152),s=n(675063);const r=()=>(0,i.createElement)(l.A,{gutter:[16,16]},(0,i.createElement)(o.A,{xs:24,sm:24},(0,i.createElement)(s.A.Input,{active:!0,size:"large",style:{margin:"20px 0"}})),[...Array(6)].map((e,t)=>(0,i.createElement)(o.A,{xs:24,sm:12,md:8,key:t},(0,i.createElement)(a.A,{style:{borderRadius:8}},(0,i.createElement)(s.A.Avatar,{active:!0,size:"large",shape:"circle",style:{marginBottom:16,marginRight:16}}),(0,i.createElement)(s.A.Input,{style:{width:200,marginBottom:8},active:!0}),(0,i.createElement)(s.A.Input,{style:{width:120,marginBottom:8},active:!0}),(0,i.createElement)("div",{style:{display:"flex",gap:10,alignItems:"center",marginTop:16}},(0,i.createElement)(s.A.Button,{style:{width:100},active:!0}),(0,i.createElement)(s.A.Button,{style:{width:100},active:!0}))))))},319575(e,t,n){n.d(t,{A:()=>l});var i=n(152619),a=n(527723),o=n(864282);const l=async(e,t)=>{try{const n=await o.A.extensions.updateExtension({name:e,status:t});return(0,i.doAction)("eventin_notification",{type:"success",message:n?.message}),!0}catch(e){return(0,i.doAction)("eventin_notification",{type:"error",message:e?.message||(0,a.__)("Update failed! Please check the plugin list and try again.","eventin")}),!1}}},749603(e,t,n){n.d(t,{A:()=>d});var i=n(951609),a=n(547143),o=n(81636),l=n(885890),s=n(404629),r=n(544207),c=n(701907);const d=()=>{const{modalType:e}=(0,a.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:t}=(0,a.useDispatch)("eventin/global"),n=()=>t({modalType:null,modalProps:null});return e&&{[o.e.ZOOM_CONFIG]:(0,i.createElement)(c.A,{open:!0,onCancel:n}),[o.e.GOOGLE_MEET_CONFIG]:(0,i.createElement)(r.A,{open:!0,onCancel:n}),[o.e.EVENTIN_AI_CONFIG]:(0,i.createElement)(l.A,{open:!0,onCancel:n}),[o.e.GOOGLE_MAP_CONFIG]:(0,i.createElement)(s.A,{open:!0,onCancel:n})}[e]||null}},222423(e,t,n){n.d(t,{A:()=>E});var i=n(951609),a=n(929491),o=n(547143),l=n(986087),s=n(527723),r=n(16370),c=n(892911),d=n(747152),g=n(167313),m=n(506660);const{Title:u,Text:p}=g.A,_=(0,o.withDispatch)((e,t,{select:n})=>{const i=e("eventin/global");return{invalidateExtensions:()=>i.invalidateResolution("getExtensions"),invalidateSettings:()=>{i.invalidateResolution("getSettings"),n("eventin/global").getSettings()}}}),v=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),E=(0,a.compose)(v,_)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:a,invalidateSettings:o}=e||{},[g,_]=(0,l.useState)([]);return(0,l.useEffect)(()=>{t&&_(Array.isArray(t)&&t?.filter(e=>"addon"===e.type)||[])},[t]),(0,i.createElement)("div",{className:"etn-module-section"},(0,i.createElement)(d.A,{gutter:[30,30]},(0,i.createElement)(r.A,{span:24},(0,i.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,i.createElement)(u,{level:3,className:"etn-extension-title"},(0,s.__)("Addons","eventin")),(0,i.createElement)(p,{className:"etn-extension-description"}," ",(0,s.__)("Eventin addons","eventin")))),g.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n,invalidateSettings:o})))))})},885890(e,t,n){n.d(t,{A:()=>m});var i=n(951609),a=n(527723),o=n(929491),l=n(547143),s=n(152619),r=n(760742),c=n(75093),d=n(864282);const g=(0,l.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,o.compose)(g)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[g]=r.A.useForm(),{integrationLoading:m}=(0,l.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:u,invalidateResolution:p}=(0,l.useDispatch)("eventin/global"),_=Array.isArray(o)&&o?.find(e=>"eventin_ai"===e.slug),{data:v={}}=_||{data:{}},{eventin_ai_auth_key:E}=v||{},x=async()=>{try{const e=g.getFieldsValue();u({integrationLoading:!0}),(await d.A.settings.updateSettings(e)).eventin_ai_auth_key&&(0,s.doAction)("eventin_notification",{type:"success",message:(0,a.__)("Open AI key updated successfully","eventin")}),p("getExtensions"),n()}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{u({integrationLoading:!1,modalType:null})}};return(0,i.createElement)(c.ExtensionConfigModal,{open:t,onCancel:n,title:(0,a.__)("Eventin AI Configure","eventin"),onConnect:x,width:500,loading:m,form:g},(0,i.createElement)(r.A,{form:g,layout:"vertical",onFinish:x,initialValues:{eventin_ai_auth_key:E}},(0,i.createElement)(c.TextInputPassword,{label:(0,a.__)("Open AI Key","eventin"),name:"eventin_ai_auth_key",placeholder:(0,a.__)("Enter Open AI Key","eventin"),required:!0,type:"password",rules:[{required:!0,message:(0,a.__)("Open AI Key is required","eventin")}]})))})},924581(e,t,n){n.d(t,{A:()=>r});var i=n(951609),a=n(956427),o=n(892911),l=n(818062),s=n(327154);function r(e){const{title:t}=e;return(0,i.createElement)(a.Fill,{name:s.PRIMARY_HEADER_NAME},(0,i.createElement)(o.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,i.createElement)(l.A,{title:t})))}},704436(e,t,n){n.d(t,{A:()=>d});var i=n(951609),a=n(986087),o=n(311804),l=n(217026),s=n(81636),r=n(698739);const c=s.l.map(e=>({value:e.key,label:(0,i.createElement)("span",{className:"etn-segment-label"},e.icon,e.label)})),d=function(e){const{activeTab:t,setActiveTab:n,extensions:d}=e||{},[g,m]=(0,a.useState)(!0);if((0,a.useEffect)(()=>{null!=d&&m(!1)},[d]),g)return(0,i.createElement)(l.A,null);const u=s.l.find(e=>e.key===t)||s.l[0];return(0,i.createElement)("div",{className:"etn-extensions-container"},(0,i.createElement)(r.GP,null,(0,i.createElement)(o.A,{value:t,onChange:e=>n(e),options:c,className:"etn-segment-nav"})),(0,i.createElement)(r.nA,null,(0,i.createElement)(r.JS,{key:t},u.children)))}},404629(e,t,n){n.d(t,{A:()=>m});var i=n(951609),a=n(527723),o=n(929491),l=n(547143),s=n(152619),r=n(760742),c=n(75093),d=n(864282);const g=(0,l.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,o.compose)(g)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[g]=r.A.useForm(),{integrationLoading:m}=(0,l.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:u,invalidateResolution:p}=(0,l.useDispatch)("eventin/global"),_=Array.isArray(o)&&o?.find(e=>"google_map"===e.slug),{data:v={}}=_||{data:{}},{google_api_key:E}=v||{},x=async()=>{try{const e=g.getFieldsValue();u({integrationLoading:!0}),(await d.A.settings.updateSettings(e)).google_api_key&&(0,s.doAction)("eventin_notification",{type:"success",message:(0,a.__)("Google Map API key updated successfully","eventin")}),p("getExtensions"),n()}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{u({integrationLoading:!1})}};return(0,i.createElement)(c.ExtensionConfigModal,{open:t,onCancel:n,title:(0,a.__)("Google Map Configure","eventin"),onConnect:x,width:500,loading:m,form:g},(0,i.createElement)(r.A,{form:g,layout:"vertical",onFinish:x,initialValues:{google_api_key:E}},(0,i.createElement)(c.TextInputPassword,{label:(0,a.__)("Map API Key","eventin"),name:"google_api_key",placeholder:(0,a.__)("Enter Map API Key","eventin"),tooltip:(0,a.__)("Map API Key","eventin"),required:!0,rules:[{required:!0,message:(0,a.__)("Map API Key is required","eventin")}]})))})},544207(e,t,n){n.d(t,{A:()=>u});var i=n(951609),a=n(547143),o=n(929491),l=n(152619),s=n(527723),r=n(760742),c=n(75093),d=n(864282),g=n(354725);const m=(0,a.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,o.compose)(m)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[m]=r.A.useForm(),{integrationLoading:u}=(0,a.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:p,invalidateResolution:_}=(0,a.useDispatch)("eventin/global"),v=Array.isArray(o)&&o?.find(e=>"google_meet"===e.slug),{data:E={}}=v||{data:{}},{google_meet_client_id:x,google_meet_client_secret_key:f,google_meet_redirect_url:h}=E||{},y=async()=>{try{const e=m.getFieldsValue();p({integrationLoading:!0});const t=await d.A.settings.updateSettings(e);_("getExtensions"),t.google_meet_authorize_url&&(window.location.href=t.google_meet_authorize_url)}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e.message})}finally{p({integrationLoading:!1})}};return(0,i.createElement)(c.ExtensionConfigModal,{open:t,onCancel:n,title:(0,s.__)("Google Meet Configure","eventin"),onConnect:y,width:500,loading:u,form:m},(0,i.createElement)(r.A,{form:m,layout:"vertical",onFinish:y,initialValues:{google_meet_client_id:x,google_meet_client_secret_key:f,google_meet_redirect_url:h}},(0,i.createElement)(c.TextInputPassword,{label:(0,s.__)("Client ID","eventin"),name:"google_meet_client_id",placeholder:(0,s.__)("Enter Client ID","eventin"),tooltip:(0,s.__)("Enter Client ID","eventin"),required:!0,type:"password",rules:[{required:!0,message:(0,s.__)("Client ID is required","eventin")}]}),(0,i.createElement)(c.TextInputPassword,{label:(0,s.__)("Client Secret Key","eventin"),name:"google_meet_client_secret_key",placeholder:(0,s.__)("Enter Client Secret Key","eventin"),tooltip:(0,s.__)("Enter Client Secret Key","eventin"),required:!0,type:"password",rules:[{required:!0,message:(0,s.__)("Client Secret Key is required","eventin")}]}),(0,i.createElement)(r.A.Item,{label:(0,s.__)("Authorized Redirect URL","eventin"),name:"google_meet_redirect_url"},(0,i.createElement)(c.InputFieldWithCopyButton,{copyText:h,buttonTooltipText:(0,s.__)("Copy Redirect URL","eventin"),icon:(0,i.createElement)(g.CopyIconOutlined,null),placeholder:(0,s.__)("Enter redirect url","eventin")}))))})},220710(e,t,n){n.d(t,{A:()=>E});var i=n(951609),a=n(929491),o=n(547143),l=n(986087),s=n(527723),r=n(16370),c=n(892911),d=n(747152),g=n(167313),m=n(506660);const{Title:u,Text:p}=g.A,_=(0,o.withDispatch)(e=>{const t=e("eventin/global");return{invalidateExtensions:()=>t.invalidateResolution("getExtensions")}}),v=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),E=(0,a.compose)(v,_)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:a}=e||{},[o,g]=(0,l.useState)([]);return(0,l.useEffect)(()=>{t&&g(Array.isArray(t)&&t?.filter(e=>"integration"===e.type)||[])},[t]),(0,i.createElement)("div",{className:"etn-module-section"},(0,i.createElement)(d.A,{gutter:[30,30]},(0,i.createElement)(r.A,{span:24},(0,i.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,i.createElement)(u,{level:3,className:"etn-extension-title"},(0,s.__)("Integrations","eventin")),(0,i.createElement)(p,{className:"etn-extension-description"}," ",(0,s.__)("Third-party integrations","eventin")))),o.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n})))))})},864945(e,t,n){n.d(t,{A:()=>E});var i=n(951609),a=n(929491),o=n(547143),l=n(986087),s=n(527723),r=n(16370),c=n(892911),d=n(747152),g=n(167313),m=n(506660);const{Title:u,Text:p}=g.A,_=(0,o.withDispatch)((e,t,{select:n})=>{const i=e("eventin/global");return{invalidateExtensions:()=>i.invalidateResolution("getExtensions"),invalidateSettings:()=>{i.invalidateResolution("getSettings"),n("eventin/global").getSettings()}}}),v=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),E=(0,a.compose)(v,_)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:a,invalidateSettings:o}=e||{},[g,_]=(0,l.useState)([]),[v,E]=(0,l.useState)([]),[x,f]=(0,l.useState)([]);return(0,l.useEffect)(()=>{t&&(_(Object.values(t).filter(e=>"module"===e.type)),E(Object.values(t).filter(e=>"addon"===e.type)),f(Object.values(t).filter(e=>"integration"===e.type)))},[t]),(0,i.createElement)("div",{className:"etn-module-section"},(0,i.createElement)(d.A,{gutter:[30,30]},(0,i.createElement)(r.A,{span:24},(0,i.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,i.createElement)(u,{level:3,className:"etn-extension-title"},(0,s.__)("Modules","eventin")),(0,i.createElement)(p,{className:"etn-extension-description"}," ",(0,s.__)("Eventin modules","eventin")))),g.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n})))),(0,i.createElement)(d.A,{gutter:[30,30]},(0,i.createElement)(r.A,{span:24},(0,i.createElement)(c.A,{justify:"space-between",align:"center",gap:10,style:{marginTop:"30px"}},(0,i.createElement)(u,{level:3,className:"etn-extension-title"},(0,s.__)("Addons","eventin")),(0,i.createElement)(p,{className:"etn-extension-description"}," ",(0,s.__)("Eventin addons","eventin")))),v.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n,invalidateSettings:o})))),(0,i.createElement)(d.A,{gutter:[30,30]},(0,i.createElement)(r.A,{span:24},(0,i.createElement)(c.A,{justify:"space-between",align:"center",gap:10,style:{marginTop:"30px"}},(0,i.createElement)(u,{level:3,className:"etn-extension-title"},(0,s.__)("Integrations","eventin")),(0,i.createElement)(p,{className:"etn-extension-description"}," ",(0,s.__)("Eventin integrations","eventin")))),x.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n})))))})},137762(e,t,n){n.d(t,{v:()=>g});var i=n(951609),a=n(527723),o=n(167313),l=n(311883);const{Text:s}=o.A,r=()=>(0,i.createElement)("svg",{width:"16",height:"16",viewBox:"0 0 16 16",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("circle",{cx:"8",cy:"8",r:"7",stroke:"currentColor",strokeWidth:"1.5"}),(0,i.createElement)("path",{d:"M6.5 5.5L11 8L6.5 10.5V5.5Z",fill:"currentColor"})),c={display:"inline-flex",alignItems:"center",gap:"6px",fontSize:"14px",fontWeight:"500",color:"#4b5563",textDecoration:"none",cursor:"pointer",transition:"color 0.2s ease"},d=({href:e,children:t})=>(0,i.createElement)("a",{href:e,target:"_blank",rel:"noreferrer",style:c,onMouseEnter:e=>e.currentTarget.style.color="#1677ff",onMouseLeave:e=>e.currentTarget.style.color="#4b5563"},t),g=({description:e,notice:t,doc_link:n,video_link:o})=>(0,i.createElement)("div",{className:"etn-card-desc",style:{marginBottom:"20px"}},(0,i.createElement)(s,null,e.length>90?e.slice(0,90).concat("..."):e),t&&(0,i.createElement)(s,{style:{display:"flex",color:"#ff7129",marginTop:"10px"}},t),(0,i.createElement)("div",{style:{display:"flex",alignItems:"center",gap:"16px",marginTop:"14px"}},(0,i.createElement)(d,{href:n},(0,i.createElement)(l.A,null)," ",(0,a.__)("Read More","eventin")),o&&(0,i.createElement)(d,{href:o},(0,i.createElement)(r,null)," ",(0,a.__)("Tutorial","eventin"))))},532066(e,t,n){n.d(t,{x:()=>v});var i=n(951609),a=n(986087),o=n(527723),l=n(547143),s=n(650400),r=n(892911),c=n(607638),d=n(864282),g=n(152619),m=n(808358);const u={fontSize:"16px",padding:"20px"},p={backgroundColor:"#F3F4F6"},_="zoom",v=e=>{const{type:t,isProActive:n,is_pro:v,deps:E,loading:x,data:f}=e,{setEventinState:h}=(0,l.useDispatch)("eventin/global"),{actions:y,btnStyle:A}=(({status:e,type:t,slug:n,onChangeStatus:i,upgrade_link:l,settings_link:s,deps:r,upgrade:d,setEventinState:g})=>{const m=(0,a.useCallback)(()=>{g({modalType:n})},[n,g]),_={on:()=>"integration"===t||"eventin_ai"===n?[{label:(0,o.__)("Configure","eventin"),variant:c.Vt,style:p,onClick:m}]:r?.length||"addon"===t?[{label:(0,o.__)("Install","eventin"),variant:c.zB,onClick:()=>i("install")}]:[],install:()=>[{label:(0,o.__)("Activate","eventin"),variant:c.zB,onClick:()=>i("activate")}],upgrade:()=>[{label:(0,o.__)("Download","eventin"),variant:c.zB,href:l,target:"_blank"}],activate:()=>[{label:(0,o.__)("Deactivate","eventin"),variant:c.Vt,onClick:()=>i("deactivate")},s&&{label:(0,o.__)("Configure","eventin"),variant:c.Vt,href:s,target:"_blank"}].filter(Boolean)};return{actions:_[e]?.()||[],btnStyle:u}})({...e,setEventinState:h}),[b,w]=(0,a.useState)(!1),[k,C]=(0,a.useState)({zoom_connected:"yes"===f?.zoom_connected,google_meet_connected:"yes"===f?.google_meet_connected}),L=(0,a.useCallback)(async e=>{try{w(!0),await d.A.settings.updateSettings(e)&&(C(t=>({...t,zoom_connected:e?.zoom_connected,google_meet_connected:e?.google_meet_connected})),(0,g.doAction)("eventin_notification",{type:"success",message:(0,o.__)("Disconnected successfully","eventin")}))}catch(e){(0,g.doAction)("eventin_notification",{type:"error",message:e.message})}finally{w(!1)}},[]),S=(0,a.useCallback)((e,n,a)=>"integration"===t&&n?(0,i.createElement)(s.Ay,{style:A,variant:e===_?c.Vt:c.zB,onClick:()=>L(a),loading:b,disabled:b},(0,o.__)("Disconnect","eventin")):null,[t,A,L,b]);return(0,a.useMemo)(()=>{if(!n&&v)return(0,i.createElement)(m.A,{sx:{height:"36px",fontSize:"14px"}});if("module"===t&&!E?.length)return null;if(!y.length)return null;const e=S(_,k?.zoom_connected,{zoom_token:{},zoom_connected:!1}),a=S("google_meet",k?.google_meet_connected,{google_token:{},google_meet_connected:!1});return(0,i.createElement)(r.A,{gap:20,wrap:"wrap"},e,a,y.map((e,t)=>(0,i.createElement)(s.Ay,{key:t,...e,style:{...A,...e.style||{}},loading:x},e.label)))},[y,A,E,x,n,v,t,k,S])}},270334(e,t,n){n.d(t,{j:()=>o});var i=n(951609),a=n(43960);const o=({checked:e,loading:t,disabled:n,onChange:o})=>(0,i.createElement)(a.A,{className:"etn-addon-module-switch",loading:t,checked:e,onChange:o,disabled:n})},506660(e,t,n){n.d(t,{A:()=>g});var i=n(951609),a=n(167313),o=(n(957584),n(125280)),l=n(698739),s=n(137762),r=n(532066),c=n(270334);const{Title:d}=a.A,g=({module:e,invalidateExtensions:t,isExtensionsLoading:n,invalidateSettings:a})=>{const{name:g,title:m,description:u,status:p,notice:_,icon:v,settings_link:E,doc_link:x,video_link:f,is_pro:h,upgrade_link:y,upgrade:A,deps:b,type:w,slug:k,badge_tags:C=[],data:L={}}=e||{},{status:S,isLoading:I,buttonLoading:z,isActive:T,toggleModule:M,updateStatus:R}=(0,o.A)(g,p,t,n,a),N=!!window.localized_data_obj.evnetin_pro_active;return(0,i.createElement)(l.vi,null,(0,i.createElement)("div",{className:"etn-module-card-header"},(0,i.createElement)("div",{className:"etn-module-card-header-icon",dangerouslySetInnerHTML:{__html:v}}),!(!N&&h)&&(0,i.createElement)(c.j,{checked:T,onChange:M,loading:I})),(0,i.createElement)("div",{className:"etn-module-card-body"},(0,i.createElement)("div",{style:{display:"flex",alignItems:"center",flexWrap:"wrap",gap:"8px",margin:"0 0 10px 0"}},(0,i.createElement)(d,{level:4,style:{margin:0,fontSize:"20px"}},m),C.map(e=>(0,i.createElement)(l.Ij,{key:e,variant:e.toLowerCase()},e))),(0,i.createElement)(s.v,{description:u,notice:_,doc_link:x,video_link:f})),(0,i.createElement)(l.dQ,{isFooter:!0},(0,i.createElement)(r.x,{status:p,loading:z,onChangeStatus:R,upgrade:A,upgrade_link:y,settings_link:E,type:w,slug:k,deps:b,is_pro:h,isProActive:N,data:L})))}},701907(e,t,n){n.d(t,{A:()=>u});var i=n(951609),a=n(547143),o=n(527723),l=n(929491),s=n(152619),r=n(760742),c=n(75093),d=n(864282),g=n(354725);const m=(0,a.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,l.compose)(m)(e=>{const{open:t,onCancel:n,extensionsList:l}=e,[m]=r.A.useForm(),{integrationLoading:u}=(0,a.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:p,invalidateResolution:_}=(0,a.useDispatch)("eventin/global"),v=Array.isArray(l)&&l?.find(e=>"zoom"===e.slug),{data:E={}}=v||{data:{}},{zoom_redirect_url:x}=E||{},f=async()=>{try{const e=m.getFieldsValue();p({integrationLoading:!0});const t=await d.A.settings.updateSettings(e);_("getExtensions"),t.zoom_authorize_url&&(window.location.href=t.zoom_authorize_url)}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{p({integrationLoading:!1})}};return(0,i.createElement)(c.ExtensionConfigModal,{open:t,onCancel:n,title:(0,o.__)("Zoom Configure","eventin"),onConnect:f,width:500,loading:u,form:m},(0,i.createElement)(r.A,{form:m,layout:"vertical",onFinish:f,initialValues:{zoom_client_id:E?.zoom_client_id,zoom_client_secret:E?.zoom_client_secret,zoom_redirect_url:E?.zoom_redirect_url}},(0,i.createElement)(c.TextInputPassword,{label:(0,o.__)("Client ID","eventin"),name:"zoom_client_id",placeholder:(0,o.__)("Enter Client ID","eventin"),tooltip:(0,o.__)("Enter Client ID","eventin"),required:!0,rules:[{required:!0,message:(0,o.__)("Client ID is required","eventin")}]}),(0,i.createElement)(c.TextInputPassword,{label:(0,o.__)("Client Secret Key","eventin"),name:"zoom_client_secret",placeholder:(0,o.__)("Enter Client Secret Key","eventin"),tooltip:(0,o.__)("Enter Client Secret Key","eventin"),required:!0,rules:[{required:!0,message:(0,o.__)("Client Secret Key is required","eventin")}]}),(0,i.createElement)(r.A.Item,{label:(0,o.__)("Redirect URL","eventin"),name:"zoom_redirect_url"},(0,i.createElement)(c.InputFieldWithCopyButton,{copyText:x||"",buttonTooltipText:(0,o.__)("Copy Redirect URL","eventin"),icon:(0,i.createElement)(g.CopyIconOutlined,null),placeholder:(0,o.__)("Enter redirect url","eventin")}))))})},81636(e,t,n){n.d(t,{e:()=>g,l:()=>d});var i=n(951609),a=n(527723),o=n(167313),l=n(864945),s=n(220710),r=n(222423);const{Title:c}=o.A,d=[{key:"1",label:(0,a.__)("Extensions","eventin"),icon:(0,i.createElement)(()=>(0,i.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("path",{d:"M7.5 2.25C7.5 1.42157 8.17157 0.75 9 0.75C9.82843 0.75 10.5 1.42157 10.5 2.25V3H12.75C13.5784 3 14.25 3.67157 14.25 4.5V6.75H15C15.8284 6.75 16.5 7.42157 16.5 8.25C16.5 9.07843 15.8284 9.75 15 9.75H14.25V12.75C14.25 13.5784 13.5784 14.25 12.75 14.25H10.5V15C10.5 15.8284 9.82843 16.5 9 16.5C8.17157 16.5 7.5 15.8284 7.5 15V14.25H5.25C4.42157 14.25 3.75 13.5784 3.75 12.75V9.75H3C2.17157 9.75 1.5 9.07843 1.5 8.25C1.5 7.42157 2.17157 6.75 3 6.75H3.75V4.5C3.75 3.67157 4.42157 3 5.25 3H7.5V2.25Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,i.createElement)(l.A,null)},{key:"2",label:(0,a.__)("Integrations","eventin"),icon:(0,i.createElement)(()=>(0,i.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("path",{d:"M6.75 3.75H4.5C3.67157 3.75 3 4.42157 3 5.25V13.5C3 14.3284 3.67157 15 4.5 15H13.5C14.3284 15 15 14.3284 15 13.5V11.25M11.25 3.75H14.25M14.25 3.75V6.75M14.25 3.75L8.25 9.75",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,i.createElement)(s.A,null)},{key:"3",label:(0,a.__)("Addons","eventin"),icon:(0,i.createElement)(()=>(0,i.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("path",{d:"M3 3.75H7.5V8.25H3V3.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,i.createElement)("path",{d:"M10.5 3.75H15V8.25H10.5V3.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,i.createElement)("path",{d:"M3 9.75H7.5V14.25H3V9.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,i.createElement)("path",{d:"M12.75 9.75V14.25M10.5 12H15",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,i.createElement)(r.A,null)}],g={ZOOM_CONFIG:"zoom",GOOGLE_MEET_CONFIG:"google_meet",EVENTIN_AI_CONFIG:"eventin_ai",GOOGLE_MAP_CONFIG:"google_map"}},125280(e,t,n){n.d(t,{A:()=>o});var i=n(986087),a=n(319575);function o(e,t,n,o,l){const[s,r]=(0,i.useState)(t),[c,d]=(0,i.useState)(!1),[g,m]=(0,i.useState)(!1);return{status:s,isLoading:c,isActive:"off"!==s,buttonLoading:g,toggleModule:async t=>{d(!0);const i=await(0,a.A)(e,t?"on":"off");i&&(r(t?"on":"off"),await n()),setTimeout(()=>!o&&d(!1),1500),(i&&"eventin-addon-for-surecart"===e||"eventin-addon-for-fluentcart"===e)&&l()},updateStatus:async t=>{m(!0),await(0,a.A)(e,t)&&await n(),setTimeout(()=>!o&&m(!1),1500)}}}},725046(e,t,n){n.r(t),n.d(t,{default:()=>p});var i=n(951609),a=n(929491),o=n(547143),l=n(986087),s=n(527723),r=n(75093),c=n(749603),d=n(924581),g=n(704436),m=n(698739);const u=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),p=(0,a.compose)(u)(function(e){const{extensions:t,isExtensionsLoading:n}=e,[a,o]=(0,l.useState)("1");return(0,i.createElement)(m.ff,{className:"eventin-page-wrapper"},(0,i.createElement)(d.A,{title:(0,s.__)("Extensions","eventin")}),(0,i.createElement)(g.A,{activeTab:a,setActiveTab:o,extensions:t,isExtensionsLoading:n}),(0,i.createElement)(c.A,null),(0,i.createElement)(r.FloatingHelpButton,null))})},698739(e,t,n){n.d(t,{GP:()=>l,Ij:()=>m,JS:()=>s,dQ:()=>d,ff:()=>o,nA:()=>r,vi:()=>c});var i=n(327154),a=n(769815);const o=a.default.div`
	background-color: #f4f6fa;
	padding: 12px 32px;
	min-height: 100vh;

	.addons-area-heading {
		width: 50%;
		margin-bottom: 30px;
		@media ( max-width: 768px ) {
			width: 100%;
		}
	}
`,l=a.default.div`
	background: #fff;
	border-radius: 8px;
	margin-bottom: 16px;
	padding: 16px 24px;

	.etn-segment-nav {
		&.ant-segmented {
			background: #f1f1f4;
			border-radius: 10px;
			padding: 4px;
			box-shadow: none;
		}

		.ant-segmented-item {
			border-radius: 7px;
			color: #6b7280;
			font-size: 14px;
			font-weight: 500;
			transition: color 0.5s ease;

			&:hover:not( .ant-segmented-item-selected ) {
				color: #7c3aed;
			}
		}

		.ant-segmented-item-selected {
			color: #7c3aed;
			box-shadow: 0 1px 6px rgba( 0, 0, 0, 0.12 );
		}

		.ant-segmented-thumb {
			border-radius: 7px;
			background: #fff;
		}

		.ant-segmented-item-label {
			padding: 6px 18px;
			min-height: 36px;
			display: flex;
			align-items: center;
		}

		.etn-segment-label {
			display: inline-flex;
			align-items: center;
			gap: 8px;
		}
	}
`,s=a.default.div`
	@keyframes etn-tab-fade-in {
		0% {
			opacity: 0;
			transform: translateY( 12px ) scale( 0.99 );
		}
		60% {
			opacity: 1;
		}
		100% {
			opacity: 1;
			transform: translateY( 0 ) scale( 1 );
		}
	}

	animation: etn-tab-fade-in 0.45s cubic-bezier( 0.22, 1, 0.36, 1 ) forwards;
`,r=a.default.div`
	background: #fff;
	border-radius: 8px;
	margin-bottom: 30px;
	padding: 30px;
	@media ( max-width: 768px ) {
		padding: 20px;
	}
	.etn-extension-title {
		font-size: 20px;
		display: inline-block;
		color: #212327;
		font-weight: 600;
	}
	.etn-extension-description {
		font-size: 14px;
		color: #6b7280;
		font-weight: 400;
	}
`,c=a.default.div`
	border-radius: 8px;
	margin: 0;
	min-height: 350px;
	overflow: hidden;
	position: relative;
	border: 1px solid #d9d9d9;
	display: flex;
	flex-direction: column;
	justify-content: space-between;

	.etn-module-card-header {
		padding: 20px;
		display: flex;
		justify-content: space-between;
		gap: 20px;
		@media ( max-width: 768px ) {
			flex-wrap: wrap;
		}
	}
	.etn-module-card-header-icon {
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 8px;
	}
	.etn-module-card-body,
	.etn-module-card-footer {
		padding: 0 20px;
	}

	.etn-card-desc {
		font-size: 14px;
		color: #838790;
		.etn-doc-link {
			color: ${i.PRIMARY_COLOR};
			margin-top: 20px;
			a {
				display: inline-flex;
				gap: 8px;
				font-size: 16px !important;
				font-weight: 600 !important;
				text-decoration: none !important;
			}
		}
	}
	.etn-link-button {
		color: ${i.PRIMARY_COLOR};
		font-size: 15px;
		font-weight: 600;
		margin-top: 10px;
		text-decoration: underline;
		&:hover {
			text-decoration: underline;
			color: ${i.PRIMARY_COLOR};
		}
	}
	@media ( max-width: 768px ) {
		.ant-card .ant-card-body {
			padding: 40px 10px;
		}
	}
	.ant-switch .ant-switch-loading-icon.anticon {
		position: relative;
		top: -2px;
		color: rgba( 0, 0, 0, 0.65 );
		vertical-align: top;
	}
`,d=a.default.div`
	height: ${({isFooter:e})=>e?"70px":"0px"};
	display: flex;
	justify-content: flex-end;
	align-items: center;
	gap: 16px;
	padding-inline: 20px;
`,g=(a.default.span`
	font-size: 24px;
	margin-right: 10px;
`,{free:"background: #dcfce7; color: #16a34a;",popular:"background: #fef9c3; color: #a16207;",new:"background: #dbeafe; color: #1d4ed8;",pro:"background: #f3e8ff; color: #7c3aed;",hot:"background: #fee2e2; color: #dc2626;",trending:"background: #fff7ed; color: #c2410c;",default:"background: #f3f4f6; color: #374151;"}),m=a.default.span`
	display: inline-flex;
	align-items: center;
	padding: 2px 10px;
	border-radius: 4px;
	font-size: 12px;
	font-weight: 500;
	line-height: 1.6;
	${({variant:e})=>g[e]||g.default}
`;a.default.div`
	position: absolute;
	height: 85px;
	width: 60px;
	transform: rotate( -45deg );
	top: -38px;
	right: -22px;
	background-color: #faad14;
	color: #fff;
	padding: 5px 16px;
	.anticon {
		position: absolute;
		top: 38px;
		left: 7px;
		transform: rotate( 45deg );
	}
`}}]);