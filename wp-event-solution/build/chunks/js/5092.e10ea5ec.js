"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[5092],{45092(e,t,n){n.r(t),n.d(t,{default:()=>h});var o=n(51609),a=n(86087),r=n(27723),i=n(82654),l=n(50400),s=n(38181),c=n(60742),d=n(79888),p=n(19549),m=n(64282),u=n(90984),x=n(91397),g=n(27579);function h({open:e,onClose:t,prompt:n}){const h=(0,x.T1)(),[b]=c.A.useForm(),[f,w]=(0,a.useState)(!1),[v,A]=(0,a.useState)(null),E=c.A.useWatch("consented",b),y=c.A.useWatch("email",b);return(0,a.useEffect)(()=>{e&&(b.resetFields(),A(null))},[e]),(0,o.createElement)(p.A,{open:e,onCancel:t,footer:null,width:468,centered:!0,maskClosable:!f,destroyOnHidden:!0},(0,o.createElement)(g.HM,null,(0,o.createElement)(g.wX,null,(0,o.createElement)(u.ZT,null)),(0,o.createElement)(g.L3,null,(0,r.__)("Turn on Eventin AI","wp-event-solution")),(0,o.createElement)(g.dc,null,(0,a.createInterpolateElement)((0,r.__)("Your assistant runs on the free <strong>Aisentic</strong> plugin. We will install and register it for you.","wp-event-solution"),{strong:(0,o.createElement)("strong",null)}))),(0,o.createElement)(g.QE,null,(0,o.createElement)(g.xE,null,(0,r.__)("2,000","wp-event-solution")),(0,o.createElement)(g.kq,null,(0,r.__)("free AI credits · no card, no trial","wp-event-solution"))),(0,o.createElement)(g.Nt,null,(0,o.createElement)(c.A,{form:b,layout:"vertical",initialValues:{email:h.email,consented:!1},onFinish:async e=>{A(null),w(!0);try{await m.A.extensions.updateExtension({name:"aisentic",status:"activate",connect_account:!0,account_name:h.name,email:e.email.trim(),site_url:h.siteUrl}),(0,x.E4)(),(0,x.IL)(n),window.location.reload()}catch(e){A(e?.message||(0,r.__)("Installation failed. You can install Aisentic from Plugins → Add New instead.","wp-event-solution"))}finally{w(!1)}},requiredMark:!1},(0,o.createElement)(c.A.Item,{name:"email",label:(0,r.__)("Email","wp-event-solution"),rules:[{required:!0,type:"email",message:(0,r.__)("Enter a valid email address.","wp-event-solution")}]},(0,o.createElement)(d.A,{size:"large",disabled:f,placeholder:(0,r.__)("you@example.com","wp-event-solution")})),(0,o.createElement)(g.kE,null,(0,o.createElement)(c.A.Item,{name:"consented",valuePropName:"checked",noStyle:!0},(0,o.createElement)(s.A,{disabled:f},(0,r.__)("I agree to share my name, email & site address with Aisentic to create my free account.","wp-event-solution")))),v&&(0,o.createElement)(i.A,{type:"error",showIcon:!0,style:{marginTop:16},message:v}),(0,o.createElement)(g.ii,null,(0,o.createElement)(l.A,{size:"large",onClick:t,disabled:f},(0,r.__)("Not now","wp-event-solution")),(0,o.createElement)(l.A,{type:"primary",size:"large",htmlType:"submit",loading:f,disabled:!E||!y},f?(0,r.__)("Setting up…","wp-event-solution"):(0,r.__)("Install & get 2,000 credits","wp-event-solution"))))))}},27579(e,t,n){var o=n(69815),a=n(27154);const r="var(--etn-text, #202223)",i="var(--etn-text-muted, #6D6D6D)",l="var(--etn-border-subtle, #E5E7EB)",s="var(--etn-surface, #FFFFFF)",c=o.A.h2`
	margin: 0;
	padding: 0;
	text-align: center;
	font-size: 40px;
	font-weight: 700;
	line-height: 1.15;
	letter-spacing: -0.5px;
	color: ${r};

	@media ( max-width: 768px ) {
		font-size: 30px;
	}
`,d=o.A.p`
	margin: 16px auto 0;
	max-width: 640px;
	text-align: center;
	font-size: 16px;
	line-height: 1.6;
	color: ${i};

	@media ( max-width: 768px ) {
		font-size: 15px;
	}
`,p=o.A.div`
	margin: 0 auto 40px;
	max-width: 720px;
`,m=o.A.div`
	margin: 0 auto;
	width: 100%;
	max-width: 860px;
`,u=o.A.div`
	border: 1px solid ${l};
	border-radius: 16px;
	background-color: ${s};
	padding: 20px 24px;
	box-shadow: 0 1px 2px rgba( 16, 16, 32, 0.04 );
	transition: border-color 0.2s ease;

	&:focus-within {
		border-color: ${a.VG};
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
`,x=o.A.div`
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 12px;
	margin-top: 20px;
`,g=o.A.span`
	font-size: 13px;
	color: var(--etn-text-muted, #9096a2);
`,h=o.A.div`
	display: flex;
	flex-wrap: wrap;
	justify-content: center;
	gap: 12px;
	margin-top: 24px;
`,b=o.A.button`
	cursor: pointer;
	border: 1px solid ${l};
	border-radius: 999px;
	background-color: ${s};
	padding: 9px 20px;
	font-size: 14px;
	color: var(--etn-text-secondary, #4b5162);
	box-shadow: 0 1px 2px rgba( 16, 16, 32, 0.03 );
	transition: color 0.2s ease, border-color 0.2s ease;

	&:hover {
		border-color: ${a.VG};
		color: var(--etn-primary-text, ${a.VG});
	}
`,f=o.A.div`
	text-align: center;
`,w=o.A.div`
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 52px;
	height: 52px;
	border-radius: 16px;
	background-color: ${a.VG};
	color: #fff;
`,v=o.A.h3`
	margin: 16px 0 0;
	font-size: 22px;
	font-weight: 700;
	color: ${r};
`,A=o.A.p`
	margin: 8px 0 0;
	font-size: 14px;
	line-height: 1.5;
	color: ${i};

	strong {
		color: ${r};
		font-weight: 600;
	}
`,E=o.A.div`
	margin-top: 20px;
	border-radius: 12px;
	background-color: rgba( 107, 46, 229, 0.08 );
	padding: 20px 16px;
	text-align: center;
`,y=o.A.p`
	margin: 0;
	font-size: 30px;
	font-weight: 700;
	line-height: 1;
	color: var(--etn-primary-text, ${a.VG});
`,_=o.A.p`
	margin: 8px 0 0;
	font-size: 13px;
	color: var(--etn-primary-text, ${a.VG});
	opacity: 0.7;
`,k=o.A.div`
	margin-top: 20px;

	.ant-checkbox-checked .ant-checkbox-inner {
		background-color: ${a.VG};
		border-color: ${a.VG};
	}

	.ant-checkbox:hover .ant-checkbox-inner,
	.ant-checkbox-wrapper:hover .ant-checkbox-inner {
		border-color: ${a.VG};
	}
`,$=o.A.div`
	border-radius: 10px;
	background-color: rgba( 107, 46, 229, 0.06 );
	padding: 14px;

	.ant-checkbox-wrapper {
		align-items: flex-start;
		font-size: 13px;
		line-height: 1.5;
		color: var(--etn-text-secondary, #4b5162);
	}
`,z=o.A.div`
	display: flex;
	align-items: center;
	gap: 12px;
	margin-top: 24px;

	.ant-btn:last-of-type {
		flex: 1;
	}
`;n.d(t,["DB",0,u,"G9",0,g,"HM",0,f,"L3",0,v,"Nt",0,k,"QE",0,E,"Vb",0,b,"Vv",0,c,"a_",0,m,"dc",0,A,"e3",0,p,"fA",0,d,"ii",0,z,"jR",0,h,"kE",0,$,"kq",0,_,"wX",0,w,"xE",0,y,"yC",0,x])}}]);