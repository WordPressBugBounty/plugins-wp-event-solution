"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[4809],{44809(e,t,n){n.r(t),n.d(t,{default:()=>f});var i=n(51609),r=n(86087),a=n(52619),o=n(27723),s=n(17437),c=n(50400),d=n(49658),l=n(64282),u=n(31676),p=n(42587);const v="eventin_start_tour",h={action:(0,o.__)("Action","eventin"),required:(0,o.__)("Required","eventin")};function f(){const{tourId:e,version:t,steps:n,groupTotal:f,group:g,fieldIndex:m,fieldTotal:k,index:_,open:b,isGated:x,start:w,requestStep:y,requestFinish:A,finish:O}=(0,p.A)(),[E,S]=(0,r.useState)(!1);(0,r.useEffect)(()=>{let n=!1;if(!new URLSearchParams(window.location.search).has("etn_no_tour"))return l.A.tour.getState().then(i=>{var r;if(n)return;S(!0);const a=i?.tours?.[e],o=null!==(r=i?.context)&&void 0!==r?r:{};var s,c;a&&["completed","skipped"].includes(a.status)?(null!==(s=a.version)&&void 0!==s?s:1)<t&&w({from:0}):"in_progress"!==a?.status?o.fresh_install&&w():w({from:null!==(c=a.step)&&void 0!==c?c:0})}).catch(()=>{S(!0)}),()=>{n=!0};S(!0)},[e,t,w]),(0,r.useEffect)(()=>((0,a.addAction)(v,"eventin/tour-host",()=>w({from:0})),()=>(0,a.removeAction)(v,"eventin/tour-host")),[w]);const N=(0,r.useMemo)(()=>n.map(({badge:e,title:t,...n})=>({...n,title:(0,i.createElement)(i.Fragment,null,e?(0,i.createElement)(u.a,{variant:e},h[e]):null,t)})),[n]);return E&&N.length?(0,i.createElement)(i.Fragment,null,(0,i.createElement)(s.mL,{styles:u.B}),(0,i.createElement)(d.A,{rootClassName:"etn-tour",open:b,current:_,steps:N,zIndex:10050,gap:{offset:6,radius:10},scrollIntoViewOptions:{block:"center",behavior:"instant"},onChange:e=>y(e),onClose:()=>O("skipped"),onFinish:()=>A(),indicatorsRender:()=>{const e=(0,o.sprintf)(/* translators: 1: current step number, 2: total steps. */ /* translators: 1: current step number, 2: total steps. */
(0,o.__)("Step %1$d of %2$d","eventin"),g,f);return k?(0,o.sprintf)(/* translators: 1: "Step 3 of 9", 2: current field number, 3: total fields in this stop. */ /* translators: 1: "Step 3 of 9", 2: current field number, 3: total fields in this stop. */
(0,o.__)("%1$s · field %2$d of %3$d","eventin"),e,m,k):e},actionsRender:(e,t)=>(0,i.createElement)(i.Fragment,null,t.current<t.total-1||x?(0,i.createElement)(c.A,{type:"text",size:"small",className:"etn-tour-skip",onClick:()=>O("skipped")},(0,o.__)("Skip","eventin")):null,e)})):null}},35276(e,t,n){n.d(t,{q:()=>c});var i=n(27723),r=n(52619),a=n(6836);const o="eventin-core",s=[{id:"create-event",group:1,anchor:"create-event",route:"/dashboard",title:(0,i.__)("Create your first event","eventin"),description:(0,i.__)("Everything starts here. Click Create Event.","eventin"),advanceOnRoute:"/events/create"},{id:"stage-basic",autoNavigate:!0,group:2,anchor:"stage-basic",route:"/events/create/basic",title:(0,i.__)("Basic Info first","eventin"),description:(0,i.__)("Four stages. We will fill the required fields in order.","eventin")},{id:"field-event-name",autoNavigate:!0,group:3,kind:"field",anchor:"field-event-name",route:"/events/create/basic",placement:"right",title:(0,i.__)("Event name","eventin"),description:(0,i.__)("The headline attendees see everywhere.","eventin")},{id:"field-timezone",autoNavigate:!0,group:3,kind:"field",anchor:"field-timezone",route:"/events/create/basic",placement:"right",title:(0,i.__)("Time zone","eventin"),description:(0,i.__)("Every date below is read against this. Set it first.","eventin")},{id:"field-event-dates",autoNavigate:!0,group:3,kind:"field",anchor:"field-event-dates",route:"/events/create/basic",placement:"right",title:(0,i.__)("Event dates","eventin"),description:(0,i.__)("Start and end date. A single-day event uses the same date twice.","eventin")},{id:"field-start-time",autoNavigate:!0,group:3,kind:"field",anchor:"field-start-time",route:"/events/create/basic",placement:"right",title:(0,i.__)("Start time","eventin"),description:(0,i.__)("When doors open.","eventin")},{id:"field-end-time",autoNavigate:!0,group:3,kind:"field",anchor:"field-end-time",route:"/events/create/basic",placement:"right",title:(0,i.__)("End time","eventin"),description:(0,i.__)("When it wraps up.","eventin")},{id:"field-venue-location",autoNavigate:!0,group:3,kind:"field",anchor:"field-venue-location",route:"/events/create/basic",placement:"right",title:(0,i.__)("Where it happens","eventin"),description:(0,i.__)("A venue address, or a joining link for Virtual and Hybrid.","eventin")},{id:"stage-tickets",autoNavigate:!0,group:4,anchor:"stage-tickets",route:"/events/create/basic",title:(0,i.__)("Now the tickets","eventin"),description:(0,i.__)("Open the Tickets stage.","eventin"),advanceOnRoute:"/events/create/tickets"},{id:"enable-payment",autoNavigate:!0,group:5,anchor:"enable-payment",route:"/events/create/tickets",when:()=>!(0,a.pW)(),title:(0,i.__)("Enable a payment method","eventin"),description:(0,i.__)("Paid tickets need a gateway. Opens in a new tab — come back and continue.","eventin")},{id:"add-ticket",autoNavigate:!0,group:6,anchor:"add-ticket",route:"/events/create/tickets",title:(0,i.__)("Add a ticket","eventin"),description:(0,i.__)("Even a free event needs one ticket type. Click Add Ticket.","eventin"),advanceOnAnchor:"field-ticket-name"},{id:"field-ticket-name",group:7,kind:"field",anchor:"field-ticket-name",route:"/events/create/tickets",inModal:!0,placement:"right",title:(0,i.__)("Ticket name","eventin"),description:(0,i.__)("What the buyer picks from — “General admission”.","eventin")},{id:"field-ticket-quantity",group:7,kind:"field",anchor:"field-ticket-quantity",route:"/events/create/tickets",inModal:!0,placement:"right",title:(0,i.__)("How many exist","eventin"),description:(0,i.__)("Your stock. Sales stop when it runs out, or choose unlimited.","eventin")},{id:"field-ticket-window",group:7,kind:"field",anchor:"field-ticket-window",route:"/events/create/tickets",inModal:!0,placement:"right",title:(0,i.__)("Sale window","eventin"),description:(0,i.__)("When tickets are on sale — not the event date.","eventin")},{id:"save-ticket",group:7,anchor:"save-ticket",route:"/events/create/tickets",inModal:!0,title:(0,i.__)("Save the ticket","eventin"),description:(0,i.__)("The ticket is not on the event until it is saved.","eventin"),actOnNext:!0,nextLabel:(0,i.__)("Save ticket","eventin"),advanceOnAnchorGone:"field-ticket-name"},{id:"publish",group:8,anchor:"publish",route:"/events/create",title:(0,i.__)("Publish when you are ready","eventin"),description:(0,i.__)("That is the walkthrough. Finish the remaining stages, then hit Publish to put the event live.","eventin"),nextLabel:(0,i.__)("Done","eventin"),advanceOnRouteLeave:"/events/create"}];function c(){const e={id:o,version:3,steps:s};return(0,r.applyFilters)("eventin.tours",[e])}n.d(t,["$",0,o])},31676(e,t,n){var i=n(17437),r=n(69815),a=n(27154);const o=r.A.span`
	display: inline-flex;
	align-items: center;
	height: 18px;
	padding: 0 6px;
	margin-right: 8px;
	border-radius: 4px;
	font-size: 10px;
	font-weight: 600;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	vertical-align: 2px;
	white-space: nowrap;

	${({variant:e})=>"action"===e?i.AH`
					background: var(--etn-primary-bg, #ede7fd);
					color: var(--etn-primary-text, ${a.kY});
			  `:i.AH`
					background: #fde8ef;
					color: #c11574;
			  `}
`,s=i.AH`
	/* rootClassName lands on three nodes — the popup, the mask, and the
	   spotlight placeholder antd measures the card against. Everything below has
	   to stay pinned to .ant-tour, or a width here shrinks the mask to a strip
	   and pulls the card back over the field it is describing. */
	/* antd hands the mask and the card the same z-index, so which of the two wins
	   hit testing comes down to paint order. Inside the ticket dialog the mask
	   ended up on top and its click-blocking rects swallowed presses on the
	   card's own Skip/Back/Next. Drop the mask one layer. */
	.etn-tour.ant-tour-mask {
		z-index: ${10049} !important;
	}

	.etn-tour.ant-tour {
		max-width: 384px;

		.ant-tour-inner {
			padding: 16px;
			border-radius: 12px;
			box-shadow: 0 12px 32px rgba( 16, 24, 40, 0.18 );
		}

		.ant-tour-header {
			padding: 0 24px 6px 0;
		}

		.ant-tour-title {
			font-size: 15px;
			font-weight: 600;
			line-height: 22px;
			color: var(--etn-text, #101828);
		}

		.ant-tour-description {
			padding: 0;
			font-size: 13px;
			line-height: 20px;
			color: var(--etn-text-secondary, #475467);
		}

		.ant-tour-footer {
			padding: 14px 0 0;
			margin-top: 12px;
			border-top: 1px solid var(--etn-border-subtle, #f0f0f4);
		}

		.ant-tour-indicators {
			font-size: 12px;
			font-weight: 500;
			color: var(--etn-text-muted, #667085);
			white-space: nowrap;
		}

		.ant-tour-buttons {
			display: inline-flex;
			gap: 8px;
			margin-inline-start: auto;

			.ant-btn {
				height: 28px;
				padding: 0 12px;
				border-radius: 6px;
				font-size: 12px;
				font-weight: 500;
				box-shadow: none;
			}

			.ant-btn-primary {
				background: ${a.VG};

				&:not( :disabled ):hover {
					background: ${a.kY};
				}
			}
		}

		.etn-tour-skip {
			padding: 0 4px;
			color: var(--etn-text-muted, #667085);

			&:not( :disabled ):hover {
				color: var(--etn-text, #101828);
				background: transparent;
			}
		}

		.ant-tour-close {
			top: 16px;
			inset-inline-end: 16px;
			color: var(--etn-text-disabled, #98a2b3);
		}
	}
`;n.d(t,["B",0,s,"a",0,o])},42587(e,t,n){n.d(t,{A:()=>h});var i=n(86087),r=n(27723),a=n(47767),o=n(64282),s=n(8218),c=n(35276);const d=(e,t)=>!t||e.startsWith(t),l=e=>Boolean(e?.advanceOnRoute||e?.advanceOnRouteLeave||e?.advanceOnAnchor||e?.advanceOnAnchorGone),u=()=>Array.from(document.querySelectorAll(".ant-modal-wrap")).some(e=>"none"!==e.style.display),p=e=>(null!=e?e:"").split("/").filter(Boolean).slice(0,2).join("/"),v=(e,t)=>!(e.inModal&&!u()||!d(t,e.route)&&p(t)!==p(e.route));function h(){var e,t,n,p,h;const f=(0,a.zy)(),g=(0,a.Zp)(),[m]=(0,i.useState)(()=>(0,c.q)()[0]),[k,_]=(0,i.useState)(0),[b,x]=(0,i.useState)(!1),[w,y]=(0,i.useState)(!1),[A,O]=(0,i.useState)(null),E=(0,i.useRef)(null),S=(0,i.useRef)(null),N=(0,i.useRef)(null),C=(0,i.useRef)(0),R=(0,i.useRef)(f.pathname),q=(0,i.useRef)({index:-1,pathname:null});R.current=f.pathname;const M=null!==(e=m?.steps)&&void 0!==e?e:[],T=(0,i.useCallback)((e,t)=>{o.A.tour.saveState(m.id,{status:e,step:t,version:m.version}).catch(()=>{})},[m]),z=(0,i.useCallback)(e=>!!e&&("function"!=typeof e.when||e.when()),[]),B=(0,i.useCallback)(e=>{let t=e;for(;t<M.length&&!z(M[t]);)t+=1;return t},[M,z]),F=(0,i.useCallback)(e=>{let t=e;for(;t>=0;){const e=M[t];if(z(e)&&v(e,R.current))return t;t-=1}return-1},[M,z]),L=(0,i.useMemo)(()=>{const e=M.filter(e=>z(e)),t=[],n={};return e.forEach(e=>{var i;t.includes(e.group)||t.push(e.group),"field"===e.kind&&(n[e.group]=null!==(i=n[e.group])&&void 0!==i?i:[],n[e.group].push(e.id))}),{order:t,fields:n}},[M,z]),$=(0,i.useCallback)((e="completed")=>{x(!1),y(!1),E.current||(S.current="completed"===S.current||"completed"===e?"completed":e,N.current||(N.current=setTimeout(()=>{N.current=null,E.current=S.current,T(E.current,M.length)},0)))},[T,M.length]),G=(0,i.useCallback)(e=>{const t=e<C.current,n=t?F(e):B(e);t&&n<0||(!t&&n>=M.length?$("completed"):(E.current=null,S.current=null,C.current=n,y(!1),_(n),T("in_progress",n)))},[B,F,M.length,$,T]),I=(0,i.useCallback)(()=>{N.current&&(clearTimeout(N.current),N.current=null),S.current=null,E.current=null,x(!0),y(!0)},[]),P=(0,i.useCallback)(e=>{const t=M[C.current];if(t?.actOnNext&&e>C.current)return I(),void(e=>{if(!e)return;const t=e.matches('button, a, [role="button"]')?e:e.querySelector('button, a, [role="button"]');t?.click()})((0,s.t1)(t.anchor));G(e)},[M,G,I]),W=(0,i.useCallback)(()=>P(M.length),[P,M.length]),H=(0,i.useCallback)(({from:e=0}={})=>{if(!M.length)return;const t=B(e),n=M[t];!n||n.autoNavigate||d(R.current,n.route)?(O(null),C.current=t,_(t),x(!0)):O(t)},[M,B]);(0,i.useEffect)(()=>{if(null===A)return;const e=M[A];e?d(f.pathname,e.route)&&(O(null),C.current=A,_(A),x(!0)):O(null)},[A,M,f.pathname]),(0,i.useEffect)(()=>{if(!b)return;const e=M[k];if(!e)return void $("completed");let t=!1;return(async()=>{if(!d(f.pathname,e.route))return e.autoNavigate?void g(e.route):void y(!1);if(e.inModal&&!u())return void G(k+1);y(!1);const n=await(0,s.K1)(e.anchor,{timeout:3e3});if(!t){if(!n)return(0,s.M0)(e.id,e.anchor),void G(k+1);n.scrollIntoView({block:"center",behavior:"instant"}),q.current={index:k,pathname:f.pathname},y(!0)}})(),()=>{t=!0}},[b,k,M,f.pathname,g,$,G]),(0,i.useEffect)(()=>{if(!b||!w)return;const e=M[k];if(!e)return;const t=q.current.index===k&&q.current.pathname===f.pathname;if(e.advanceOnRoute&&!t&&d(f.pathname,e.advanceOnRoute))return void G(k+1);if(e.advanceOnRouteLeave&&!d(f.pathname,e.advanceOnRouteLeave))return void G(k+1);if(!e.advanceOnAnchor&&!e.advanceOnAnchorGone)return;let n=e.advanceOnAnchor?!!(0,s.t1)(e.advanceOnAnchor):!(0,s.t1)(e.advanceOnAnchorGone);const i=new MutationObserver(()=>{if(e.advanceOnAnchor){const t=!!(0,s.t1)(e.advanceOnAnchor);return n?void(n=t):void(t&&G(k+1))}const t=!(0,s.t1)(e.advanceOnAnchorGone);n?n=t:t&&G(k+1)});return i.observe(document.body,{childList:!0,subtree:!0,attributes:!0,attributeFilter:["style","class"]}),()=>i.disconnect()},[b,w,k,M,f.pathname,G]),(0,i.useEffect)(()=>{if(!b||!w)return;const e=M[k];if(!e?.inModal)return;if(e.advanceOnAnchorGone)return;const t=new MutationObserver(()=>{u()||$("skipped")});return t.observe(document.body,{childList:!0,subtree:!0,attributes:!0,attributeFilter:["style"]}),()=>t.disconnect()},[b,w,k,M,$]);const V=(0,i.useMemo)(()=>M.map((e,t)=>{var n;return{title:e.title,description:e.description,badge:"field"===e.kind?"required":l(e)?"action":null,placement:e.placement,target:()=>(0,s.t1)(e.anchor),nextButtonProps:{children:null!==(n=e.nextLabel)&&void 0!==n?n:t===M.length-1?(0,r.__)("Finish","eventin"):(0,r.__)("Next","eventin")},prevButtonProps:{children:(0,r.__)("Back","eventin")}}}),[M]),Y=M[k],j=null!==(t=L.fields[Y?.group])&&void 0!==t?t:[],D=b&&w&&k>0&&F(k-1)>=0,K=(0,i.useMemo)(()=>V.map((e,t)=>t===k?{...e,prevButtonProps:{...e.prevButtonProps,disabled:!D}}:e),[V,k,D]);return{tourId:null!==(n=m?.id)&&void 0!==n?n:c.$,version:null!==(p=m?.version)&&void 0!==p?p:1,steps:K,groupTotal:L.order.length,group:Y?L.order.indexOf(Y.group)+1:0,fieldIndex:"field"===Y?.kind?j.indexOf(Y.id)+1:0,fieldTotal:"field"===Y?.kind?j.length:0,badge:null!==(h=V[k]?.badge)&&void 0!==h?h:null,index:k,open:b&&w,isLast:k>=M.length-1,isGated:Boolean(Y?.actOnNext),start:H,requestStep:P,requestFinish:W,finish:$}}}}]);