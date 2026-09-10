"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[5092],{45092(e,t,n){n.r(t),n.d(t,{default:()=>h});var a=n(51609),i=n(86087),o=n(27723),r=n(82654),l=n(50400),s=n(38181),c=n(60742),d=n(79888),p=n(19549),m=n(64282),x=n(90984),u=n(91397),g=n(27579);function h({open:e,onClose:t,prompt:n}){const h=(0,u.T1)(),[b]=c.A.useForm(),[f,w]=(0,i.useState)(!1),[v,A]=(0,i.useState)(null),E=c.A.useWatch("consented",b),k=c.A.useWatch("email",b);return(0,i.useEffect)(()=>{e&&(b.resetFields(),A(null))},[e]),(0,a.createElement)(p.A,{open:e,onCancel:t,footer:null,width:468,centered:!0,maskClosable:!f,destroyOnHidden:!0},(0,a.createElement)(g.HM,null,(0,a.createElement)(g.wX,null,(0,a.createElement)(x.ZT,null)),(0,a.createElement)(g.L3,null,(0,o.__)("Turn on Eventin AI","eventin")),(0,a.createElement)(g.dc,null,(0,i.createInterpolateElement)((0,o.__)("Your assistant runs on the free <strong>Aisentic</strong> plugin. We will install and register it for you.","eventin"),{strong:(0,a.createElement)("strong",null)}))),(0,a.createElement)(g.QE,null,(0,a.createElement)(g.xE,null,(0,o.__)("150,000","eventin")),(0,a.createElement)(g.kq,null,(0,o.__)("free AI tokens · no card, no trial","eventin"))),(0,a.createElement)(g.Nt,null,(0,a.createElement)(c.A,{form:b,layout:"vertical",initialValues:{email:h.email,consented:!1},onFinish:async e=>{A(null),w(!0);try{await m.A.extensions.updateExtension({name:"aisentic",status:"activate",connect_account:!0,account_name:h.name,email:e.email.trim(),site_url:h.siteUrl}),(0,u.E4)(),(0,u.IL)(n),window.location.reload()}catch(e){A(e?.message||(0,o.__)("Installation failed. You can install Aisentic from Plugins → Add New instead.","eventin"))}finally{w(!1)}},requiredMark:!1},(0,a.createElement)(c.A.Item,{name:"email",label:(0,o.__)("Email","eventin"),rules:[{required:!0,type:"email",message:(0,o.__)("Enter a valid email address.","eventin")}]},(0,a.createElement)(d.A,{size:"large",disabled:f,placeholder:(0,o.__)("you@example.com","eventin")})),(0,a.createElement)(g.kE,null,(0,a.createElement)(c.A.Item,{name:"consented",valuePropName:"checked",noStyle:!0},(0,a.createElement)(s.A,{disabled:f},(0,o.__)("I agree to share my name, email & site address with Aisentic to create my free account.","eventin")))),v&&(0,a.createElement)(r.A,{type:"error",showIcon:!0,style:{marginTop:16},message:v}),(0,a.createElement)(g.ii,null,(0,a.createElement)(l.A,{size:"large",onClick:t,disabled:f},(0,o.__)("Not now","eventin")),(0,a.createElement)(l.A,{type:"primary",size:"large",htmlType:"submit",loading:f,disabled:!E||!k},f?(0,o.__)("Setting up…","eventin"):(0,o.__)("Install & get 150k tokens","eventin"))))))}},27579(e,t,n){var a=n(69815),i=n(27154);const o="#202223",r="#6D6D6D",l="#E5E7EB",s="#FFFFFF",c=a.A.h2`
	margin: 0;
	padding: 0;
	text-align: center;
	font-size: 40px;
	font-weight: 700;
	line-height: 1.15;
	letter-spacing: -0.5px;
	color: ${o};

	@media ( max-width: 768px ) {
		font-size: 30px;
	}
`,d=a.A.p`
	margin: 16px auto 0;
	max-width: 640px;
	text-align: center;
	font-size: 16px;
	line-height: 1.6;
	color: ${r};

	@media ( max-width: 768px ) {
		font-size: 15px;
	}
`,p=a.A.div`
	margin: 0 auto 40px;
	max-width: 720px;
`,m=a.A.div`
	margin: 0 auto;
	width: 100%;
	max-width: 860px;
`,x=a.A.div`
	border: 1px solid ${l};
	border-radius: 16px;
	background-color: ${s};
	padding: 20px 24px;
	box-shadow: 0 1px 2px rgba( 16, 16, 32, 0.04 );
	transition: border-color 0.2s ease;

	&:focus-within {
		border-color: ${i.VG};
	}

	/*
	 * The composer owns the frame, so the textarea inside is chrome-less.
	 *
	 * The !important flags are load-bearing: this renders inside WP admin's .wrap,
	 * whose textarea and textarea:focus rules (1px border plus a 1px box-shadow in
	 * link blue) outrank antd's borderless variant and draw a second box inside the
	 * card. Note for editors: no backticks in this comment — it lives inside an
	 * emotion template literal, and one would end the string.
	 */
	.ant-input {
		font-size: 15px;
		line-height: 1.6;
		padding: 0;
		resize: none;
		border: 0 !important;
		background: transparent !important;
		box-shadow: none !important;
		outline: none !important;

		&:hover,
		&:focus,
		&:focus-visible,
		&:focus-within {
			border: 0 !important;
			box-shadow: none !important;
			outline: none !important;
		}
	}
`,u=a.A.div`
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 12px;
	margin-top: 20px;
`,g=a.A.span`
	font-size: 13px;
	color: #9096a2;
`,h=a.A.div`
	display: flex;
	flex-wrap: wrap;
	justify-content: center;
	gap: 12px;
	margin-top: 24px;
`,b=a.A.button`
	cursor: pointer;
	border: 1px solid ${l};
	border-radius: 999px;
	background-color: ${s};
	padding: 9px 20px;
	font-size: 14px;
	color: #4b5162;
	box-shadow: 0 1px 2px rgba( 16, 16, 32, 0.03 );
	transition: color 0.2s ease, border-color 0.2s ease;

	&:hover {
		border-color: ${i.VG};
		color: ${i.VG};
	}
`,f=a.A.div`
	text-align: center;
`,w=a.A.div`
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 52px;
	height: 52px;
	border-radius: 16px;
	background-color: ${i.VG};
	color: #fff;
`,v=a.A.h3`
	margin: 16px 0 0;
	font-size: 22px;
	font-weight: 700;
	color: ${o};
`,A=a.A.p`
	margin: 8px 0 0;
	font-size: 14px;
	line-height: 1.5;
	color: ${r};

	strong {
		color: ${o};
		font-weight: 600;
	}
`,E=a.A.div`
	margin-top: 20px;
	border-radius: 12px;
	background-color: rgba( 107, 46, 229, 0.08 );
	padding: 20px 16px;
	text-align: center;
`,k=a.A.p`
	margin: 0;
	font-size: 30px;
	font-weight: 700;
	line-height: 1;
	color: ${i.VG};
`,_=a.A.p`
	margin: 8px 0 0;
	font-size: 13px;
	color: ${i.VG};
	opacity: 0.7;
`,y=a.A.div`
	margin-top: 20px;

	.ant-checkbox-checked .ant-checkbox-inner {
		background-color: ${i.VG};
		border-color: ${i.VG};
	}

	.ant-checkbox:hover .ant-checkbox-inner,
	.ant-checkbox-wrapper:hover .ant-checkbox-inner {
		border-color: ${i.VG};
	}
`,$=a.A.div`
	border-radius: 10px;
	background-color: rgba( 107, 46, 229, 0.06 );
	padding: 14px;

	.ant-checkbox-wrapper {
		align-items: flex-start;
		font-size: 13px;
		line-height: 1.5;
		color: #4b5162;
	}
`,z=a.A.div`
	display: flex;
	align-items: center;
	gap: 12px;
	margin-top: 24px;

	.ant-btn:last-of-type {
		flex: 1;
	}
`;n.d(t,["DB",0,x,"G9",0,g,"HM",0,f,"L3",0,v,"Nt",0,y,"QE",0,E,"Vb",0,b,"Vv",0,c,"a_",0,m,"dc",0,A,"e3",0,p,"fA",0,d,"ii",0,z,"jR",0,h,"kE",0,$,"kq",0,_,"wX",0,w,"xE",0,k,"yC",0,u])}}]);