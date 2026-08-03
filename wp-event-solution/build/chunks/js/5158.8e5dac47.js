"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[5158],{59673(e,t,n){n.d(t,{Ay:()=>A});var l=n(51609),i=n(27723),a=n(18537),r=n(16370),o=n(60742),s=n(31058),c=n(47152),m=n(36492),d=n(90070),p=n(43960),u=n(75093),_=n(26124),v=n(7638),h=n(4506),g=n(26322),E=n(64122);const x=({title:e,description:t})=>(0,l.createElement)(g.aR,null,(0,l.createElement)("div",null,(0,l.createElement)("h3",null,e),(0,l.createElement)("p",null,t))),f=({title:e,description:t})=>(0,l.createElement)("div",{style:{marginTop:4,marginBottom:16,paddingTop:20,borderTop:"1px solid #EDF1F7"}},(0,l.createElement)("h4",{style:{fontSize:15,fontWeight:600,color:"#0B1420",margin:"0 0 2px"}},e),t?(0,l.createElement)("p",{style:{fontSize:13,color:"#5C728D",margin:0}},t):null);function A({form:e,eventList:t}){const n=o.A.useWatch("discount_type",e),p=o.A.useWatch("restricted_events",e)||[],A=Array.isArray(t)?t:t?.items||[],y=A.map(e=>({label:(0,a.decodeEntities)(e.title)||`#${e.id}`,value:Number(e.id)})),b=(0,E.ix)(p,A);return(0,l.createElement)(l.Fragment,null,(0,l.createElement)(g.t_,{title:(0,l.createElement)(x,{title:(0,i.__)("Coupon Details","eventin"),description:(0,i.__)("Set the coupon code, discount, and when it can be redeemed.","eventin")})},(0,l.createElement)(c.A,{gutter:[16,0]},(0,l.createElement)(r.A,{xs:24,md:12},(0,l.createElement)(o.A.Item,{label:(0,i.__)("Coupon code","eventin"),required:!0},(0,l.createElement)(d.A.Compact,{style:{width:"100%"},className:"eventin-coupon-code"},(0,l.createElement)(o.A.Item,{name:"code",noStyle:!0,rules:[{required:!0,message:(0,i.__)("Coupon code is required!","eventin")}]},(0,l.createElement)(_.A,{size:"large",placeholder:(0,i.__)("e.g. SUMMER20","eventin")})),(0,l.createElement)(v.Ay,{variant:v.Vt,onClick:()=>e.setFieldValue("code",(0,E.bA)()),sx:{height:40,flexShrink:0}},(0,i.__)("Generate","eventin"))))),(0,l.createElement)(r.A,{xs:24,md:6},(0,l.createElement)(o.A.Item,{name:"discount_type",initialValue:"percentage",label:(0,l.createElement)(l.Fragment,null,(0,i.__)("Discount type","eventin")," ",(0,l.createElement)(u.ID,{title:(0,i.__)("Percentage of the subtotal, or a fixed amount.","eventin")}))},(0,l.createElement)(m.A,{size:"large",options:[{label:(0,i.__)("Percentage (%)","eventin"),value:"percentage"},{label:(0,i.__)("Fixed amount","eventin"),value:"fixed"}]}))),(0,l.createElement)(r.A,{xs:24,md:6},(0,l.createElement)(o.A.Item,{name:"discount_value",label:(0,i.__)("Discount value","eventin"),rules:[{required:!0,message:(0,i.__)("Discount value is required!","eventin")},{type:"number",min:.01,message:(0,i.__)("Value must be greater than 0.","eventin")},..."percentage"===n?[{type:"number",max:100,message:(0,i.__)("Percentage cannot exceed 100.","eventin")}]:[]]},(0,l.createElement)(s.A,{size:"large",style:{width:"100%"},min:0,addonAfter:"fixed"===n?null:"%"})))),(0,l.createElement)(f,{title:(0,i.__)("Validity Period","eventin"),description:(0,i.__)("Choose the dates and times this coupon can be redeemed. Leave empty for no time limit.","eventin")}),(0,l.createElement)(h.A,{startDate:"start_date",endDate:"end_date",form:e,required:!1,seedDefaults:!1})),(0,l.createElement)(g.t_,{title:(0,l.createElement)(x,{title:(0,i.__)("Restrictions & Usage Limits","eventin"),description:(0,i.__)("Limit where the coupon applies and how often it can be used. Leave empty to apply everywhere.","eventin")})},(0,l.createElement)(c.A,{gutter:[16,0]},(0,l.createElement)(r.A,{xs:24,md:12},(0,l.createElement)(o.A.Item,{name:"restricted_events",label:(0,l.createElement)(l.Fragment,null,(0,i.__)("Include events","eventin")," ",(0,l.createElement)(u.ID,{title:(0,i.__)("Coupon applies only to these events. Empty = all events.","eventin")}))},(0,l.createElement)(m.A,{mode:"multiple",allowClear:!0,size:"large",options:y,placeholder:(0,i.__)("All events","eventin"),optionFilterProp:"label",maxTagCount:"responsive"}))),(0,l.createElement)(r.A,{xs:24,md:12},(0,l.createElement)(o.A.Item,{name:"excluded_events",label:(0,l.createElement)(l.Fragment,null,(0,i.__)("Exclude events","eventin")," ",(0,l.createElement)(u.ID,{title:(0,i.__)("Coupon never applies to these events, even if included above.","eventin")}))},(0,l.createElement)(m.A,{mode:"multiple",allowClear:!0,size:"large",options:y,placeholder:(0,i.__)("No excluded events","eventin"),optionFilterProp:"label",maxTagCount:"responsive"}))),(0,l.createElement)(r.A,{xs:24},(0,l.createElement)(o.A.Item,{name:"restricted_tickets",label:(0,l.createElement)(l.Fragment,null,(0,i.__)("Apply only to these tickets","eventin")," ",(0,l.createElement)(u.ID,{title:(0,i.__)("The discount applies only to these ticket types. Empty = all tickets in the selected events. Choose events above first.","eventin")}))},(0,l.createElement)(m.A,{mode:"multiple",allowClear:!0,size:"large",options:b,disabled:!p.length,placeholder:p.length?(0,i.__)("All tickets in selected events","eventin"):(0,i.__)("Select events first","eventin"),optionFilterProp:"label"}))),(0,l.createElement)(r.A,{xs:24,md:12},(0,l.createElement)(o.A.Item,{name:"discount_scope",initialValue:"total",label:(0,l.createElement)(l.Fragment,null,(0,i.__)("Discount applies to","eventin")," ",(0,l.createElement)(u.ID,{title:(0,i.__)("Ticket price only = discount just the ticket price (the selected tickets above, if any). Entire order total = discount the whole order including add-on options, even when specific tickets are selected.","eventin")}))},(0,l.createElement)(m.A,{size:"large",options:[{label:(0,i.__)("Entire order total (tickets + add-ons)","eventin"),value:"total"},{label:(0,i.__)("Ticket price only","eventin"),value:"tickets"}]})))),(0,l.createElement)(f,{title:(0,i.__)("Usage Limits","eventin"),description:(0,i.__)("Control how many times and under what conditions the coupon works.","eventin")}),(0,l.createElement)(c.A,{gutter:[16,0]},(0,l.createElement)(r.A,{xs:24,md:6},(0,l.createElement)(o.A.Item,{name:"usage_limit",label:(0,l.createElement)(l.Fragment,null,(0,i.__)("Total usage limit","eventin")," ",(0,l.createElement)(u.ID,{title:(0,i.__)("Max redemptions overall. Blank = unlimited.","eventin")}))},(0,l.createElement)(s.A,{size:"large",style:{width:"100%"},min:1,placeholder:(0,i.__)("Unlimited","eventin")}))),(0,l.createElement)(r.A,{xs:24,md:6},(0,l.createElement)(o.A.Item,{name:"per_user_limit",label:(0,l.createElement)(l.Fragment,null,(0,i.__)("Per-user limit","eventin")," ",(0,l.createElement)(u.ID,{title:(0,i.__)("Max redemptions per buyer. Blank = unlimited.","eventin")}))},(0,l.createElement)(s.A,{size:"large",style:{width:"100%"},min:1,placeholder:(0,i.__)("Unlimited","eventin")}))),(0,l.createElement)(r.A,{xs:24,md:6},(0,l.createElement)(o.A.Item,{name:"min_purchase",label:(0,i.__)("Min purchase","eventin")},(0,l.createElement)(s.A,{size:"large",style:{width:"100%"},min:0,placeholder:(0,i.__)("None","eventin")}))),(0,l.createElement)(r.A,{xs:24,md:6},(0,l.createElement)(o.A.Item,{name:"min_qty",label:(0,i.__)("Min tickets","eventin")},(0,l.createElement)(s.A,{size:"large",style:{width:"100%"},min:1,placeholder:(0,i.__)("None","eventin")}))))))}n.d(t,["b2",0,()=>(0,l.createElement)(g.t_,{title:(0,l.createElement)(x,{title:(0,i.__)("Status","eventin"),description:(0,i.__)("Turn this coupon on or off for checkout.","eventin")})},(0,l.createElement)(c.A,{justify:"space-between",align:"middle",style:{margin:0}},(0,l.createElement)(r.A,null,(0,i.__)("Active","eventin")),(0,l.createElement)(r.A,null,(0,l.createElement)(o.A.Item,{name:"active",valuePropName:"checked",initialValue:!0,noStyle:!0},(0,l.createElement)(p.A,null)))))])},8775(e,t,n){var l=n(51609),i=n(27723),a=n(29491),r=n(47143),o=n(86087),s=n(52619),c=n(60742),m=n(428),d=n(74353),p=n.n(d),u=n(26557),_=n(47767),v=n(7638),h=n(64282),g=n(68949),E=n(64861),x=n(59673),f=n(71994);const A={height:"36px",width:"36px",backgroundColor:"#fafafa",borderColor:"transparent",lineHeight:"1"},y=(0,r.withSelect)(e=>({eventList:e("eventin/global").getAllEvents()})),b=(0,r.withDispatch)(e=>{const t=e(E.e);return{refreshCouponsList:()=>t.invalidateResolution("getCouponsList")}}),w=(0,a.compose)([y,b])(function(e){const{eventList:t,refreshCouponsList:n}=e,[a]=c.A.useForm(),{id:r}=(0,_.g)(),d=(0,_.Zp)(),E=!!r,[y,b]=(0,o.useState)(!1),[w,C]=(0,o.useState)(!1);return(0,o.useEffect)(()=>{if(!r)return void a.resetFields();let e=!1;return C(!0),h.A.coupons.singleCoupon(r).then(t=>{if(!e&&t){const e=t.start_date?p()(t.start_date):null,n=t.end_date?p()(t.end_date):null;a.setFieldsValue({...t,event_range_name:e&&n?[e,n]:void 0,start_time:t.start_time||(e?e.format("hh:mm A"):void 0),end_time:t.end_time||(n?n.format("hh:mm A"):void 0),active:"inactive"!==t.status})}}).catch(()=>{e||(0,s.doAction)("eventin_notification",{type:"error",message:(0,i.__)("Could not load the coupon.","eventin")})}).finally(()=>{e||C(!1)}),()=>{e=!0}},[r]),(0,l.createElement)(c.A,{layout:"vertical",form:a,size:"large",scrollToFirstError:!0,onFinish:async()=>{b(!0);try{await a.validateFields();const e=a.getFieldsValue(!0),{start_date:t,end_date:l,start_time:o,end_time:c}=(0,g.V)(e),m={...e,start_date:t||null,end_date:l||null,start_time:o||null,end_time:c||null};delete m.event_range_name;const p=E?await h.A.coupons.updateCoupon(r,m):await h.A.coupons.createCoupon(m);if(!p?.id)throw new Error((0,i.__)("Could not save the coupon.","eventin"));n(),d("/coupons"),(0,s.doAction)("eventin_notification",{type:"success",message:E?(0,i.__)("Coupon updated successfully!","eventin"):(0,i.__)("Coupon created successfully!","eventin")})}catch(e){"duplicate"===e?.code?a.setFields([{name:"code",errors:[(0,i.__)("This coupon code already exists.","eventin")]}]):e?.errorFields||(0,s.doAction)("eventin_notification",{type:"error",message:e.message||(0,i.__)("Something went wrong.","eventin")})}finally{b(!1)}},className:"eventin-coupon-form"},(0,l.createElement)(f.Wn,null,(0,l.createElement)(f.Bm,null,(0,l.createElement)(v.Ay,{variant:v.Vt,htmlType:"button",icon:(0,l.createElement)(u.A,null),sx:A,onClick:()=>d("/coupons")}),(0,l.createElement)("h1",null,E?(0,i.__)("Edit Coupon","eventin"):(0,i.__)("New Coupon","eventin"))),(0,l.createElement)(f.lX,null,(0,l.createElement)(v.Ay,{variant:v.Vt,htmlType:"button",onClick:()=>d("/coupons")},(0,i.__)("Cancel","eventin")),(0,l.createElement)(v.Ay,{variant:v.zB,htmlType:"submit",loading:y},E?(0,i.__)("Update Coupon","eventin"):(0,i.__)("Create Coupon","eventin")))),w?(0,l.createElement)("div",{style:{padding:"60px 0",textAlign:"center"}},(0,l.createElement)(m.A,null)):(0,l.createElement)(f.rA,null,(0,l.createElement)(f.qH,null,(0,l.createElement)(x.Ay,{form:a,eventList:t})),(0,l.createElement)(f.GY,null,(0,l.createElement)(x.b2,null),(0,l.createElement)(f.yg,null,(0,l.createElement)("h4",null,(0,i.__)("Tips","eventin")),(0,l.createElement)("ul",null,(0,l.createElement)("li",null,(0,l.createElement)("span",{className:"dot"}),(0,i.__)("Leave events empty to apply the coupon to every event.","eventin")),(0,l.createElement)("li",null,(0,l.createElement)("span",{className:"dot"}),(0,i.__)("Leave the validity dates empty for no time limit.","eventin")),(0,l.createElement)("li",null,(0,l.createElement)("span",{className:"dot"}),(0,i.__)("“Apply only to these tickets” discounts just those tickets.","eventin")),(0,l.createElement)("li",null,(0,l.createElement)("span",{className:"dot"}),(0,i.__)("Native coupons are disabled when WooCommerce is your ticket payment method.","eventin")))))))});n.d(t,["A",0,w])},35158(e,t,n){n.r(t),n.d(t,{default:()=>o});var l=n(51609),i=n(75093),a=n(8775),r=n(71994);function o(){return(0,l.createElement)(r.ff,null,(0,l.createElement)(a.A,null),(0,l.createElement)(i._W,null))}},71994(e,t,n){var l=n(69815);const i=l.A.div`
	background: #f3f5f7;
	min-height: 100vh;
`,a=l.A.div`
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 10px 16px;
	flex-wrap: wrap;
	background-color: #ffffff;
	border-bottom: 1px solid #cbd8ea;
	position: sticky;
	/* Sit below the WP admin bar (32px desktop) so it isn't hidden on scroll —
	   the admin bar stops being fixed at ≤600px, where top: 0 is correct. */
	top: 32px;
	z-index: 99;
	padding: 14px 32px;

	@media ( max-width: 991px ) {
		padding: 12px 20px;
	}
	@media ( max-width: 600px ) {
		top: 0;
	}
	@media ( max-width: 480px ) {
		padding: 10px 14px;
	}
`,r=l.A.div`
	display: flex;
	align-items: center;
	gap: 12px;
	flex: 1 1 auto;
	min-width: 0;

	h1 {
		font-size: 20px;
		font-weight: 500;
		color: #373360;
		margin: 0;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
`,o=l.A.div`
	display: flex;
	align-items: center;
	gap: 8px;
	flex: 0 0 auto;
`,s=l.A.div`
	max-width: 1120px;
	margin: 0 auto;
	display: grid;
	grid-template-columns: minmax( 0, 740px ) minmax( 300px, 340px );
	gap: 20px;
	width: 100%;
	padding: 24px 24px 60px;

	/* Real CSS wins over antd's :where() + the ancestor
	   ".css-* input { border: transparent !important }" reset. */
	.eventin-coupon-code .ant-input {
		border: 1px solid rgba( 0, 0, 0, 0.3 ) !important;
		border-right: 0 !important;
		border-radius: 5px 0 0 5px !important;
	}

	/* Field labels aligned to the event-form token (16px / 500 / #373360). */
	.ant-form-item-label > label {
		font-size: 15px;
		color: #373360;
	}

	@media ( max-width: 1024px ) {
		max-width: 760px;
		grid-template-columns: minmax( 0, 1fr );
		padding: 20px 16px 40px;
		gap: 16px;
	}
	@media ( max-width: 480px ) {
		padding: 16px 12px 32px;
		gap: 12px;
	}
`,c=l.A.div`
	display: flex;
	flex-direction: column;
	gap: 20px;
	min-width: 0;
`,m=l.A.div`
	display: flex;
	flex-direction: column;
	gap: 20px;
`,d=l.A.div`
	background: #f7f8fa;
	border: 1px solid #cbd8ea;
	border-radius: 8px;
	padding: 20px;

	h4 {
		font-size: 15px;
		font-weight: 600;
		color: #0b1420;
		margin: 0 0 12px;
	}

	ul {
		list-style: none;
		margin: 0;
		padding: 0;
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	li {
		display: flex;
		gap: 8px;
		font-size: 13px;
		line-height: 1.5;
		color: #5c728d;
	}

	li .dot {
		flex: 0 0 auto;
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: #6b2ee5;
		margin-top: 6px;
	}
`;n.d(t,["Bm",0,r,"GY",0,m,"Wn",0,a,"ff",0,i,"lX",0,o,"qH",0,c,"rA",0,s,"yg",0,d])},64122(e,t,n){n.d(t,{$o:()=>o,Ae:()=>s,bA:()=>r,f3:()=>a,ix:()=>c});var l=n(27723),i=n(18537);function a(e){return e?"percentage"===e.discount_type?`${e.discount_value}%`:`${e.discount_value}`:""}function r(e=8){let t="";for(let n=0;n<e;n++)t+="ABCDEFGHJKLMNPQRSTUVWXYZ23456789"[Math.floor(32*Math.random())];return t}function o(e){const t=e?.restricted_events?.length||0;return 0===t?(0,l.__)("All events","eventin"):`${t} ${1===t?(0,l.__)("event","eventin"):(0,l.__)("events","eventin")}`}function s(e){var t;const n=null!==(t=e?.usage_count)&&void 0!==t?t:0;return null!=e?.usage_limit?`${n} / ${e.usage_limit}`:`${n} / ∞`}function c(e=[],t=[]){const n=(Array.isArray(t)?t:t?.items||[]).filter(t=>e.includes(Number(t.id))),l=[];return n.forEach(e=>{(e?.ticket_variations||[]).forEach(t=>{const n=t?.etn_ticket_slug,a=t?.etn_ticket_name;n&&l.push({label:`${(0,i.decodeEntities)(e.title)} — ${a||n}`,value:n})})}),l}}}]);