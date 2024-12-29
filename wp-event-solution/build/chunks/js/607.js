"use strict";(self.webpackChunkwp_event_solution=self.webpackChunkwp_event_solution||[]).push([[607],{43960:(e,n,t)=>{t.d(n,{A:()=>p});var a=t(51609),i=t(36962),l=t(46942),o=t.n(l),r=t(81102),c=t(89555),d=t(62279),s=t(98119),m=t(829),g=t(6866),u=t(12533);const h=a.forwardRef(((e,n)=>{const{prefixCls:t,size:l,disabled:h,loading:p,className:v,rootClassName:x,style:E,checked:f,value:k,defaultChecked:w,defaultValue:b,onChange:_}=e,A=function(e,n){var t={};for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&n.indexOf(a)<0&&(t[a]=e[a]);if(null!=e&&"function"==typeof Object.getOwnPropertySymbols){var i=0;for(a=Object.getOwnPropertySymbols(e);i<a.length;i++)n.indexOf(a[i])<0&&Object.prototype.propertyIsEnumerable.call(e,a[i])&&(t[a[i]]=e[a[i]])}return t}(e,["prefixCls","size","disabled","loading","className","rootClassName","style","checked","value","defaultChecked","defaultValue","onChange"]),[y,$]=(0,u.A)(!1,{value:null!=f?f:k,defaultValue:null!=w?w:b}),{getPrefixCls:S,direction:I,switch:C}=a.useContext(d.QO),z=a.useContext(s.A),M=(null!=h?h:z)||p,N=S("switch",t),L=a.createElement("div",{className:`${N}-handle`},p&&a.createElement(i.A,{className:`${N}-loading-icon`})),[O,R,T]=(0,g.A)(N),D=(0,m.A)(l),B=o()(null==C?void 0:C.className,{[`${N}-small`]:"small"===D,[`${N}-loading`]:p,[`${N}-rtl`]:"rtl"===I},v,x,R,T),q=Object.assign(Object.assign({},null==C?void 0:C.style),E);return O(a.createElement(c.A,{component:"Switch"},a.createElement(r.A,Object.assign({},A,{checked:y,onChange:function(){$(arguments.length<=0?void 0:arguments[0]),null==_||_.apply(void 0,arguments)},prefixCls:N,className:B,style:q,disabled:M,ref:n,loadingIcon:L}))))}));h.__ANT_SWITCH=!0;const p=h},6866:(e,n,t)=>{t.d(n,{A:()=>u});var a=t(54018),i=t(24978),l=t(25905),o=t(72183),r=t(80336);const c=e=>{const{componentCls:n,trackHeightSM:t,trackPadding:i,trackMinWidthSM:l,innerMinMarginSM:o,innerMaxMarginSM:r,handleSizeSM:c,calc:d}=e,s=`${n}-inner`,m=(0,a.zA)(d(c).add(d(i).mul(2)).equal()),g=(0,a.zA)(d(r).mul(2).equal());return{[n]:{[`&${n}-small`]:{minWidth:l,height:t,lineHeight:(0,a.zA)(t),[`${n}-inner`]:{paddingInlineStart:r,paddingInlineEnd:o,[`${s}-checked`]:{marginInlineStart:`calc(-100% + ${m} - ${g})`,marginInlineEnd:`calc(100% - ${m} + ${g})`},[`${s}-unchecked`]:{marginTop:d(t).mul(-1).equal(),marginInlineStart:0,marginInlineEnd:0}},[`${n}-handle`]:{width:c,height:c},[`${n}-loading-icon`]:{top:d(d(c).sub(e.switchLoadingIconSize)).div(2).equal(),fontSize:e.switchLoadingIconSize},[`&${n}-checked`]:{[`${n}-inner`]:{paddingInlineStart:o,paddingInlineEnd:r,[`${s}-checked`]:{marginInlineStart:0,marginInlineEnd:0},[`${s}-unchecked`]:{marginInlineStart:`calc(100% - ${m} + ${g})`,marginInlineEnd:`calc(-100% + ${m} - ${g})`}},[`${n}-handle`]:{insetInlineStart:`calc(100% - ${(0,a.zA)(d(c).add(i).equal())})`}},[`&:not(${n}-disabled):active`]:{[`&:not(${n}-checked) ${s}`]:{[`${s}-unchecked`]:{marginInlineStart:d(e.marginXXS).div(2).equal(),marginInlineEnd:d(e.marginXXS).mul(-1).div(2).equal()}},[`&${n}-checked ${s}`]:{[`${s}-checked`]:{marginInlineStart:d(e.marginXXS).mul(-1).div(2).equal(),marginInlineEnd:d(e.marginXXS).div(2).equal()}}}}}}},d=e=>{const{componentCls:n,handleSize:t,calc:a}=e;return{[n]:{[`${n}-loading-icon${e.iconCls}`]:{position:"relative",top:a(a(t).sub(e.fontSize)).div(2).equal(),color:e.switchLoadingIconColor,verticalAlign:"top"},[`&${n}-checked ${n}-loading-icon`]:{color:e.switchColor}}}},s=e=>{const{componentCls:n,trackPadding:t,handleBg:i,handleShadow:l,handleSize:o,calc:r}=e,c=`${n}-handle`;return{[n]:{[c]:{position:"absolute",top:t,insetInlineStart:t,width:o,height:o,transition:`all ${e.switchDuration} ease-in-out`,"&::before":{position:"absolute",top:0,insetInlineEnd:0,bottom:0,insetInlineStart:0,backgroundColor:i,borderRadius:r(o).div(2).equal(),boxShadow:l,transition:`all ${e.switchDuration} ease-in-out`,content:'""'}},[`&${n}-checked ${c}`]:{insetInlineStart:`calc(100% - ${(0,a.zA)(r(o).add(t).equal())})`},[`&:not(${n}-disabled):active`]:{[`${c}::before`]:{insetInlineEnd:e.switchHandleActiveInset,insetInlineStart:0},[`&${n}-checked ${c}::before`]:{insetInlineEnd:0,insetInlineStart:e.switchHandleActiveInset}}}}},m=e=>{const{componentCls:n,trackHeight:t,trackPadding:i,innerMinMargin:l,innerMaxMargin:o,handleSize:r,calc:c}=e,d=`${n}-inner`,s=(0,a.zA)(c(r).add(c(i).mul(2)).equal()),m=(0,a.zA)(c(o).mul(2).equal());return{[n]:{[d]:{display:"block",overflow:"hidden",borderRadius:100,height:"100%",paddingInlineStart:o,paddingInlineEnd:l,transition:`padding-inline-start ${e.switchDuration} ease-in-out, padding-inline-end ${e.switchDuration} ease-in-out`,[`${d}-checked, ${d}-unchecked`]:{display:"block",color:e.colorTextLightSolid,fontSize:e.fontSizeSM,transition:`margin-inline-start ${e.switchDuration} ease-in-out, margin-inline-end ${e.switchDuration} ease-in-out`,pointerEvents:"none"},[`${d}-checked`]:{marginInlineStart:`calc(-100% + ${s} - ${m})`,marginInlineEnd:`calc(100% - ${s} + ${m})`},[`${d}-unchecked`]:{marginTop:c(t).mul(-1).equal(),marginInlineStart:0,marginInlineEnd:0}},[`&${n}-checked ${d}`]:{paddingInlineStart:l,paddingInlineEnd:o,[`${d}-checked`]:{marginInlineStart:0,marginInlineEnd:0},[`${d}-unchecked`]:{marginInlineStart:`calc(100% - ${s} + ${m})`,marginInlineEnd:`calc(-100% + ${s} - ${m})`}},[`&:not(${n}-disabled):active`]:{[`&:not(${n}-checked) ${d}`]:{[`${d}-unchecked`]:{marginInlineStart:c(i).mul(2).equal(),marginInlineEnd:c(i).mul(-1).mul(2).equal()}},[`&${n}-checked ${d}`]:{[`${d}-checked`]:{marginInlineStart:c(i).mul(-1).mul(2).equal(),marginInlineEnd:c(i).mul(2).equal()}}}}}},g=e=>{const{componentCls:n,trackHeight:t,trackMinWidth:i}=e;return{[n]:Object.assign(Object.assign(Object.assign(Object.assign({},(0,l.dF)(e)),{position:"relative",display:"inline-block",boxSizing:"border-box",minWidth:i,height:t,lineHeight:`${(0,a.zA)(t)}`,verticalAlign:"middle",background:e.colorTextQuaternary,border:"0",borderRadius:100,cursor:"pointer",transition:`all ${e.motionDurationMid}`,userSelect:"none",[`&:hover:not(${n}-disabled)`]:{background:e.colorTextTertiary}}),(0,l.K8)(e)),{[`&${n}-checked`]:{background:e.switchColor,[`&:hover:not(${n}-disabled)`]:{background:e.colorPrimaryHover}},[`&${n}-loading, &${n}-disabled`]:{cursor:"not-allowed",opacity:e.switchDisabledOpacity,"*":{boxShadow:"none",cursor:"not-allowed"}},[`&${n}-rtl`]:{direction:"rtl"}})}},u=(0,o.OF)("Switch",(e=>{const n=(0,r.h1)(e,{switchDuration:e.motionDurationMid,switchColor:e.colorPrimary,switchDisabledOpacity:e.opacityLoading,switchLoadingIconSize:e.calc(e.fontSizeIcon).mul(.75).equal(),switchLoadingIconColor:`rgba(0, 0, 0, ${e.opacityLoading})`,switchHandleActiveInset:"-30%"});return[g(n),m(n),s(n),d(n),c(n)]}),(e=>{const{fontSize:n,lineHeight:t,controlHeight:a,colorWhite:l}=e,o=n*t,r=a/2,c=o-4,d=r-4;return{trackHeight:o,trackHeightSM:r,trackMinWidth:2*c+8,trackMinWidthSM:2*d+4,trackPadding:2,handleBg:l,handleSize:c,handleSizeSM:d,handleShadow:`0 2px 4px 0 ${new i.q("#00230b").setAlpha(.2).toRgbString()}`,innerMinMargin:c/2,innerMaxMargin:c+2+4,innerMinMarginSM:d/2,innerMaxMarginSM:d+2+4}}))},97607:(e,n,t)=>{t.r(n),t.d(n,{default:()=>te});var a=t(51609),i=t(29491),l=t(47143),o=t(27723),r=t(67313),c=t(75093),d=t(47152),s=t(16370),m=t(77278),g=t(75063);const u=()=>(0,a.createElement)(d.A,{gutter:[16,16]},[...Array(6)].map(((e,n)=>(0,a.createElement)(s.A,{xs:24,sm:12,md:8,key:n},(0,a.createElement)(m.A,{style:{borderRadius:8}},(0,a.createElement)(g.A.Avatar,{active:!0,size:"large",shape:"circle",style:{marginBottom:16,marginRight:16}}),(0,a.createElement)(g.A.Input,{style:{width:200,marginBottom:8},active:!0}),(0,a.createElement)(g.A.Input,{style:{width:120,marginBottom:8},active:!0}),(0,a.createElement)("div",{style:{display:"flex",gap:10,alignItems:"center",marginTop:16}},(0,a.createElement)(g.A.Button,{style:{width:100},active:!0}),(0,a.createElement)(g.A.Button,{style:{width:100},active:!0})))))));var h=t(56427),p=t(92911),v=t(79664),x=t(18062),E=t(27154);function f(e){const{title:n}=e;return(0,a.createElement)(h.Fill,{name:E.PRIMARY_HEADER_NAME},(0,a.createElement)(p.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,a.createElement)(x.A,{title:n}),(0,a.createElement)("div",{style:{display:"flex",alignItems:"center",gap:"12px"}},(0,a.createElement)(v.A,null))))}var k=t(80560),w=t(86087),b=t(6836);const _=({height:e=22,width:n=22})=>(0,b.iconCreator)((()=>(({height:e,width:n})=>(0,a.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",fill:"none",viewBox:"0 0 20 20"},(0,a.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeLinejoin:"round",strokeWidth:"1.5",d:"M6.25 4.121h7.083c.69 0 1.25.56 1.25 1.25v1.25M12.5 10.788h-5M10 14.121H7.5"}),(0,a.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeWidth:"1.5",d:"M15.414 1.667H5.256c-.414 0-.837.06-1.172.306-1.062.78-1.88 2.517-.228 4.086.464.44 1.112.6 1.75.6h9.63c.662 0 1.847.095 1.847 2.114v6.211a3.34 3.34 0 0 1-3.33 3.35H6.226c-1.836 0-3.172-1.298-3.277-3.274L2.922 4.304"})))({height:e,width:n}))),A=({height:e=22,width:n=22})=>(0,b.iconCreator)((()=>(({height:e,width:n})=>(0,a.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",width:"14",height:"14",fill:"none",viewBox:"0 0 14 14"},(0,a.createElement)("path",{fill:"#fff",fillRule:"evenodd",d:"M6.999 1.895a2.04 2.04 0 0 0-2.042 2.042v.91a57 57 0 0 1 2.042-.035c.72 0 1.39.012 2.041.035v-.91A2.04 2.04 0 0 0 7 1.895M3.79 3.937v1.036a2.51 2.51 0 0 0-1.735 2.059c-.087.641-.16 1.316-.16 2.009s.073 1.368.16 2.01c.158 1.176 1.133 2.106 2.333 2.16.834.04 1.68.06 2.61.06.932 0 1.778-.02 2.611-.06 1.2-.054 2.175-.984 2.334-2.16.086-.642.16-1.317.16-2.01s-.074-1.368-.16-2.01a2.5 2.5 0 0 0-1.736-2.057V3.936a3.208 3.208 0 1 0-6.417 0m6.125 5.098a.583.583 0 1 0-1.166 0v.006a.583.583 0 1 0 1.166 0zM7 8.452c.322 0 .583.261.583.583v.006a.583.583 0 1 1-1.167 0v-.006c0-.322.262-.583.584-.583m-1.75.583a.583.583 0 1 0-1.167 0v.006a.583.583 0 1 0 1.167 0z",clipRule:"evenodd"})))({height:e,width:n})));var y=t(7638),$=t(43960),S=t(69815);const I=S.default.div`
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
`,C=S.default.div`
	background: #fff;
	border-radius: 8px;
	margin-bottom: 30px;
	padding: 30px;
	@media ( max-width: 768px ) {
		padding: 20px;
	}
	.etn-extension-title {
		font-size: 24px;
		margin: 25px 0;
		padding: 14px 0;
		display: inline-block;
		border-bottom: 2px solid #d9d9d9;
	}
	.ant-tabs-tab {
		font-size: 18px;
		font-weight: 600;
		padding: 16px 30px;
	}
	.ant-tabs-top > .ant-tabs-nav::before {
		border-bottom: 2px solid #d9d9d9;
	}
`,z=(0,S.default)(m.A)`
	border-radius: 8px;
	margin: 0;
	min-height: 340px;
	overflow: hidden;
	position: relative;
	.etn-module-card-header {
		display: flex;
		justify-content: space-between;
		gap: 20px;
		@media ( max-width: 768px ) {
			flex-wrap: wrap;
		}
	}
	.etn-card-desc {
		font-size: 14px;
		color: #838790;
		.etn-doc-link {
			color: ${E.PRIMARY_COLOR};
			margin-top: 20px;
			a {
				display: flex;
				gap: 8px;
				font-size: 16px !important;
				font-weight: 600 !important;
				text-decoration: none !important;
			}
		}
	}
	.etn-link-button {
		color: ${E.PRIMARY_COLOR};
		font-size: 15px;
		font-weight: 600;
		margin-top: 10px;
		text-decoration: underline;
		&:hover {
			text-decoration: underline;
			color: ${E.PRIMARY_COLOR};
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
`,M=(S.default.span`
	font-size: 24px;
	margin-right: 10px;
`,S.default.div`
    position: absolute;
    height: 85px;
    width: 60px;
    transform: rotate(-45deg);
    top: -38px;
    right: -22px;
    background-color: #faad14;
    color: #fff;
    padding: 5px 16px;
	.anticon {
		position: absolute;
		top: 38px;
		left: 7px;
		transform: rotate(45deg);
	}
}
`);var N=t(52619),L=t(64282);const O=async(e,n)=>{try{const t=await L.A.extensions.updateExtension({name:e,status:n});return(0,N.doAction)("eventin_notification",{type:"success",message:t?.message}),!0}catch(e){return(0,N.doAction)("eventin_notification",{type:"error",message:e?.message||(0,o.__)("Update failed! Please check the plugin list and try again.","eventin")}),!1}},{Title:R,Text:T,Link:D}=r.A,B=({name:e,title:n,description:t,status:i,notice:l,icon:r,settings_link:d,demo_link:s,doc_link:m,is_pro:g,upgrade_link:u,upgrade:h,deps:v,invalidateExtensions:x})=>{const[E,f]=(0,w.useState)("off"!==i),[k,b]=(0,w.useState)(!1),[S,I]=(0,w.useState)(!1),C=async n=>{b(!0),f(n),await O(e,n?"on":"off")?(f(n),x()):f(!n),b(!1)},N=async n=>{I(!0),await O(e,n),I(!1),x()},L=!!window.localized_data_obj.evnetin_pro_active,D=(window.localized_data_obj.timetics_pro_active,{fontSize:"16px",padding:"6px 14px",marginTop:"20px"});return(0,a.createElement)(z,null,!L&&g&&(0,a.createElement)(M,null,(0,a.createElement)(A,null)),(0,a.createElement)("div",null,(0,a.createElement)("div",{className:"etn-module-card-header"},(0,a.createElement)("div",{dangerouslySetInnerHTML:{__html:r}}),(0,a.createElement)("div",{style:{display:"flex",alignItems:"center",justifyContent:"space-between",marginBottom:"18px"}},g&&L&&(0,a.createElement)($.A,{className:"etn-addon-module-switch",loading:k,checked:E,onChange:C,disabled:!L&&g}),"seat_map"===e&&(0,a.createElement)($.A,{className:"etn-addon-module-switch",loading:k,checked:E,onChange:C,disabled:!L&&g}))),(0,a.createElement)("div",null,(0,a.createElement)(R,{level:4,style:{margin:"10px 0",fontSize:"20px"}},n),(0,a.createElement)("div",{className:"etn-card-desc"},(0,a.createElement)("div",{style:{marginBottom:"20px"}},(0,a.createElement)(T,null,t)," ",(0,a.createElement)("br",null),l&&(0,a.createElement)(T,{style:{display:"flex",color:"#ff7129",marginTop:"10px"}},l),(0,a.createElement)("div",{className:"etn-doc-link"},(0,a.createElement)(c.LinkText,{href:m,target:"_blank"},(0,a.createElement)(_,null)," ",(0,o.__)("Documentation","eventin")))),v&&v.length>0&&L&&g&&(0,a.createElement)(a.Fragment,null,"on"==i&&(0,a.createElement)(y.Ay,{variant:y.zB,onClick:()=>{N("install")},target:"_blank",sx:D,loading:S},(0,o.__)("Install","eventin")),"install"==i&&(0,a.createElement)(y.Ay,{variant:y.zB,onClick:()=>{N("activate")},target:"_blank",sx:D,loading:S},(0,o.__)("Activate","eventin")),"upgrade"==i&&h&&(0,a.createElement)(y.Ay,{variant:y.zB,href:u,target:"_blank",sx:D,loading:S},(0,o.__)("Download","eventin")),"activate"==i&&(0,a.createElement)(p.A,{gap:20,wrap:"wrap"},(0,a.createElement)(y.Ay,{variant:y.Vt,onClick:()=>{N("deactivate")},target:"_blank",sx:D,loading:S},(0,o.__)("Deactivate","eventin")),d&&(0,a.createElement)(y.Ay,{variant:y.Vt,target:"_blank",sx:D,href:d},(0,o.__)("Configure","eventin")))),!L&&g&&(0,a.createElement)(y.Oc,null),v&&v.length>0&&"seat_map"===e&&(0,a.createElement)(a.Fragment,null,"on"==i&&(0,a.createElement)(y.Ay,{variant:y.zB,onClick:()=>{N("install")},target:"_blank",sx:D,loading:S},(0,o.__)("Install","eventin")),"install"==i&&(0,a.createElement)(y.Ay,{variant:y.zB,onClick:()=>{N("activate")},target:"_blank",sx:D,loading:S},(0,o.__)("Activate","eventin")),"upgrade"==i&&h&&(0,a.createElement)(y.Ay,{variant:y.zB,href:u,target:"_blank",sx:D,loading:S},(0,o.__)("Download","eventin")),"activate"==i&&(0,a.createElement)(p.A,{gap:20,wrap:"wrap"},(0,a.createElement)(y.Ay,{variant:y.Vt,onClick:()=>{N("deactivate")},target:"_blank",sx:D,loading:S},(0,o.__)("Deactivate","eventin")),d&&(0,a.createElement)(y.Ay,{variant:y.Vt,target:"_blank",sx:D,href:d},(0,o.__)("Configure","eventin"))))))))},q=(0,l.withDispatch)((e=>{const n=e("eventin/global");return{invalidateExtensions:()=>n.invalidateResolution("getExtensions")}})),H=(0,l.withSelect)((e=>{const n=e("eventin/global");return{extensions:n.getExtensions(),isExtensionsLoading:n.isResolving("getExtensions")}})),j=(0,i.compose)(H,q)((e=>{const{extensions:n,isExtensionsLoading:t,invalidateExtensions:i}=e||{},[l,o]=(0,w.useState)([]);return(0,w.useEffect)((()=>{if(n){const e=Object.values(n).filter((e=>"module"===e.type));o(e)}}),[n]),(0,a.createElement)("div",{className:"etn-module-section"},(0,a.createElement)(d.A,{gutter:[30,30]},l.map((e=>(0,a.createElement)(s.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(B,{...e,invalidateExtensions:i}))))))})),{Title:P,Text:V,Link:W}=r.A,F=({name:e,title:n,description:t,status:i,icon:l,demo_link:r,is_pro:d,doc_link:s,upgrade_link:m,upgrade:g,notice:u,invalidateExtensions:h})=>{const[v,x]=(0,w.useState)("off"!==i),[E,f]=(0,w.useState)(!1),[k,b]=(0,w.useState)(!1),S=async n=>{f(!0),await O(e,n?"on":"off")?(x(n),h()):x(!n),f(!1)},I=async n=>{b(!0),await O(e,n),b(!1),h()},C=!!window.localized_data_obj.evnetin_pro_active,N={fontSize:"16px",padding:"6px 14px",marginTop:"20px"};return(0,a.createElement)(z,null,!C&&"eventin-divi-addon"!==e&&(0,a.createElement)(M,null,(0,a.createElement)(A,null)),(0,a.createElement)("div",null,(0,a.createElement)("div",{className:"etn-module-card-header"},(0,a.createElement)("div",{dangerouslySetInnerHTML:{__html:l}}),C&&d&&(0,a.createElement)($.A,{className:"etn-addon-module-switch",loading:E,checked:v,onChange:S}),"eventin-divi-addon"==e&&(0,a.createElement)($.A,{className:"etn-addon-module-switch",loading:E,checked:v,onChange:S})),(0,a.createElement)("div",null,(0,a.createElement)(P,{level:4,style:{margin:"10px 0",fontSize:"20px"}},n),(0,a.createElement)("div",{className:"etn-card-desc",style:{marginRight:"30px"}},(0,a.createElement)("div",{style:{marginBottom:"20px"}},(0,a.createElement)(V,null,t)," ",(0,a.createElement)("br",null),u&&(0,a.createElement)(V,{style:{display:"flex",color:"#ff7129",marginTop:"10px"}},u),(0,a.createElement)("div",{className:"etn-doc-link"},(0,a.createElement)(c.LinkText,{href:s,target:"_blank"},(0,a.createElement)(_,null)," ",(0,o.__)("Documentation","eventin")))),"on"==i&&(0,a.createElement)(y.Ay,{variant:y.zB,onClick:()=>{I("install")},target:"_blank",sx:N,loading:k},(0,o.__)("Install","eventin")),"install"==i&&(0,a.createElement)(y.Ay,{variant:y.zB,onClick:()=>{I("activate")},target:"_blank",sx:N,loading:k},(0,o.__)("Activate","eventin")),"upgrade"==i&&g&&(0,a.createElement)(y.Ay,{variant:y.zB,href:m,target:"_blank",sx:N,loading:k},(0,o.__)("Download","eventin")),"activate"==i&&(0,a.createElement)(p.A,{gap:20,wrap:"wrap"},(0,a.createElement)(y.Ay,{variant:y.Vt,onClick:()=>{I("deactivate")},target:"_blank",sx:N,loading:k},(0,o.__)("Deactivate","eventin"))),!C&&"eventin-divi-addon"!==e&&(0,a.createElement)("div",{style:{marginTop:"20px"}},r&&(0,a.createElement)(c.ProButton,null))))))},{Title:X,Text:K,Link:Y}=r.A,Q=(0,l.withDispatch)((e=>{const n=e("eventin/global");return{invalidateExtensions:()=>n.invalidateResolution("getExtensions")}})),G=(0,l.withSelect)((e=>{const n=e("eventin/global");return{extensions:n.getExtensions(),isExtensionsLoading:n.isResolving("getExtensions")}})),U=(0,i.compose)(G,Q)((e=>{const{extensions:n,isExtensionsLoading:t,invalidateExtensions:i}=e||{},[l,o]=(0,w.useState)([]);return(0,w.useEffect)((()=>{if(n){const e=Object.values(n).filter((e=>"addon"===e.type));o(e)}}),[n]),(0,a.createElement)("div",{className:"etn-module-section"},(0,a.createElement)(d.A,{gutter:[30,30]},l.map((e=>(0,a.createElement)(s.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(F,{...e,invalidateExtensions:i}))))))})),{Title:J}=r.A,Z=function(){const e=[{key:"1",label:(0,o.__)("All","eventin"),children:(0,a.createElement)(a.Fragment,null,(0,a.createElement)(J,{level:3,className:"etn-extension-title"},(0,o.__)("Modules","eventin")),(0,a.createElement)(j,null),(0,a.createElement)(J,{level:3,className:"etn-extension-title"},(0,o.__)("Addons","eventin")),(0,a.createElement)(U,null))},{key:"2",label:(0,o.__)("Modules","eventin"),children:(0,a.createElement)(a.Fragment,null,(0,a.createElement)(J,{level:3,className:"etn-extension-title"},(0,o.__)("Modules","eventin")),(0,a.createElement)(j,null))},{key:"3",label:(0,o.__)("Addons","eventin"),children:(0,a.createElement)(a.Fragment,null,(0,a.createElement)(J,{level:3,className:"etn-extension-title"},(0,o.__)("Addons","eventin")),(0,a.createElement)(U,null))}];return(0,a.createElement)("div",{className:"etn-extensions-container"},(0,a.createElement)(C,null,(0,a.createElement)(k.A,{defaultActiveKey:"1",items:e})))},{Title:ee}=r.A,ne=(0,l.withSelect)((e=>{const n=e("eventin/global");return{extensions:n.getExtensions(),isExtensionsLoading:n.isResolving("getExtensions")}})),te=(0,i.compose)(ne)((function(e){const{extensions:n,isExtensionsLoading:t}=e;return(0,a.createElement)(I,{className:"eventin-page-wrapper"},(0,a.createElement)(f,{title:(0,o.__)("Extensions","eventin")}),n&&!t?(0,a.createElement)(Z,null):(0,a.createElement)(u,null))}))},81102:(e,n,t)=>{t.d(n,{A:()=>h});var a=t(58168),i=t(64467),l=t(3453),o=t(80045),r=t(51609),c=t(46942),d=t.n(c),s=t(12533),m=t(16928),g=["prefixCls","className","checked","defaultChecked","disabled","loadingIcon","checkedChildren","unCheckedChildren","onClick","onChange","onKeyDown"],u=r.forwardRef((function(e,n){var t,c=e.prefixCls,u=void 0===c?"rc-switch":c,h=e.className,p=e.checked,v=e.defaultChecked,x=e.disabled,E=e.loadingIcon,f=e.checkedChildren,k=e.unCheckedChildren,w=e.onClick,b=e.onChange,_=e.onKeyDown,A=(0,o.A)(e,g),y=(0,s.A)(!1,{value:p,defaultValue:v}),$=(0,l.A)(y,2),S=$[0],I=$[1];function C(e,n){var t=S;return x||(I(t=e),null==b||b(t,n)),t}var z=d()(u,h,(t={},(0,i.A)(t,"".concat(u,"-checked"),S),(0,i.A)(t,"".concat(u,"-disabled"),x),t));return r.createElement("button",(0,a.A)({},A,{type:"button",role:"switch","aria-checked":S,disabled:x,className:z,ref:n,onKeyDown:function(e){e.which===m.A.LEFT?C(!1,e):e.which===m.A.RIGHT&&C(!0,e),null==_||_(e)},onClick:function(e){var n=C(!S,e);null==w||w(n,e)}}),E,r.createElement("span",{className:"".concat(u,"-inner")},r.createElement("span",{className:"".concat(u,"-inner-checked")},f),r.createElement("span",{className:"".concat(u,"-inner-unchecked")},k)))}));u.displayName="Switch";const h=u}}]);