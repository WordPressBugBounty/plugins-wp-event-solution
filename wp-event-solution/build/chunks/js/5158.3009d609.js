"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[5158],{59673(e,t,n){n.d(t,{Ay:()=>f});var l=n(51609),o=n(27723),i=n(18537),a=n(16370),r=n(60742),s=n(31058),c=n(47152),p=n(36492),m=n(90070),u=n(43960),d=n(75093),_=n(26124),v=n(7638),h=n(4506),g=n(26322),x=n(64122);const w=({title:e,description:t})=>(0,l.createElement)(g.aR,null,(0,l.createElement)("div",null,(0,l.createElement)("h3",null,e),(0,l.createElement)("p",null,t))),E=({title:e,description:t})=>(0,l.createElement)("div",{style:{marginTop:4,marginBottom:16,paddingTop:20,borderTop:"1px solid var(--etn-border-subtle, #EDF1F7)"}},(0,l.createElement)("h4",{style:{fontSize:15,fontWeight:600,color:"var(--etn-text, #0B1420)",margin:"0 0 2px"}},e),t?(0,l.createElement)("p",{style:{fontSize:13,color:"var(--etn-text-muted, #5C728D)",margin:0}},t):null);function f({form:e,eventList:t}){const n=r.A.useWatch("discount_type",e),u=r.A.useWatch("restricted_events",e)||[],f=Array.isArray(t)?t:t?.items||[],A=f.map(e=>({label:(0,i.decodeEntities)(e.title)||`#${e.id}`,value:Number(e.id)})),y=(0,x.ix)(u,f);return(0,l.createElement)(l.Fragment,null,(0,l.createElement)(g.t_,{title:(0,l.createElement)(w,{title:(0,o.__)("Coupon Details","wp-event-solution"),description:(0,o.__)("Set the coupon code, discount, and when it can be redeemed.","wp-event-solution")})},(0,l.createElement)(c.A,{gutter:[16,0]},(0,l.createElement)(a.A,{xs:24,md:12},(0,l.createElement)(r.A.Item,{label:(0,o.__)("Coupon code","wp-event-solution"),required:!0},(0,l.createElement)(m.A.Compact,{style:{width:"100%"},className:"eventin-coupon-code"},(0,l.createElement)(r.A.Item,{name:"code",noStyle:!0,rules:[{required:!0,message:(0,o.__)("Coupon code is required!","wp-event-solution")}]},(0,l.createElement)(_.A,{size:"large",placeholder:(0,o.__)("e.g. SUMMER20","wp-event-solution")})),(0,l.createElement)(v.Ay,{variant:v.Vt,onClick:()=>e.setFieldValue("code",(0,x.bA)()),sx:{height:40,flexShrink:0}},(0,o.__)("Generate","wp-event-solution"))))),(0,l.createElement)(a.A,{xs:24,md:6},(0,l.createElement)(r.A.Item,{name:"discount_type",initialValue:"percentage",label:(0,l.createElement)(l.Fragment,null,(0,o.__)("Discount type","wp-event-solution")," ",(0,l.createElement)(d.ID,{title:(0,o.__)("Percentage of the subtotal, or a fixed amount.","wp-event-solution")}))},(0,l.createElement)(p.A,{size:"large",options:[{label:(0,o.__)("Percentage (%)","wp-event-solution"),value:"percentage"},{label:(0,o.__)("Fixed amount","wp-event-solution"),value:"fixed"}]}))),(0,l.createElement)(a.A,{xs:24,md:6},(0,l.createElement)(r.A.Item,{name:"discount_value",label:(0,o.__)("Discount value","wp-event-solution"),rules:[{required:!0,message:(0,o.__)("Discount value is required!","wp-event-solution")},{type:"number",min:.01,message:(0,o.__)("Value must be greater than 0.","wp-event-solution")},..."percentage"===n?[{type:"number",max:100,message:(0,o.__)("Percentage cannot exceed 100.","wp-event-solution")}]:[]]},(0,l.createElement)(s.A,{size:"large",style:{width:"100%"},min:0,addonAfter:"fixed"===n?null:"%"})))),(0,l.createElement)(E,{title:(0,o.__)("Validity Period","wp-event-solution"),description:(0,o.__)("Choose the dates and times this coupon can be redeemed. Leave empty for no time limit.","wp-event-solution")}),(0,l.createElement)(h.A,{startDate:"start_date",endDate:"end_date",form:e,required:!1,seedDefaults:!1})),(0,l.createElement)(g.t_,{title:(0,l.createElement)(w,{title:(0,o.__)("Restrictions & Usage Limits","wp-event-solution"),description:(0,o.__)("Limit where the coupon applies and how often it can be used. Leave empty to apply everywhere.","wp-event-solution")})},(0,l.createElement)(c.A,{gutter:[16,0]},(0,l.createElement)(a.A,{xs:24,md:12},(0,l.createElement)(r.A.Item,{name:"restricted_events",label:(0,l.createElement)(l.Fragment,null,(0,o.__)("Include events","wp-event-solution")," ",(0,l.createElement)(d.ID,{title:(0,o.__)("Coupon applies only to these events. Empty = all events.","wp-event-solution")}))},(0,l.createElement)(p.A,{mode:"multiple",allowClear:!0,size:"large",options:A,placeholder:(0,o.__)("All events","wp-event-solution"),optionFilterProp:"label",maxTagCount:"responsive"}))),(0,l.createElement)(a.A,{xs:24,md:12},(0,l.createElement)(r.A.Item,{name:"excluded_events",label:(0,l.createElement)(l.Fragment,null,(0,o.__)("Exclude events","wp-event-solution")," ",(0,l.createElement)(d.ID,{title:(0,o.__)("Coupon never applies to these events, even if included above.","wp-event-solution")}))},(0,l.createElement)(p.A,{mode:"multiple",allowClear:!0,size:"large",options:A,placeholder:(0,o.__)("No excluded events","wp-event-solution"),optionFilterProp:"label",maxTagCount:"responsive"}))),(0,l.createElement)(a.A,{xs:24},(0,l.createElement)(r.A.Item,{name:"restricted_tickets",label:(0,l.createElement)(l.Fragment,null,(0,o.__)("Apply only to these tickets","wp-event-solution")," ",(0,l.createElement)(d.ID,{title:(0,o.__)("The discount applies only to these ticket types. Empty = all tickets in the selected events. Choose events above first.","wp-event-solution")}))},(0,l.createElement)(p.A,{mode:"multiple",allowClear:!0,size:"large",options:y,disabled:!u.length,placeholder:u.length?(0,o.__)("All tickets in selected events","wp-event-solution"):(0,o.__)("Select events first","wp-event-solution"),optionFilterProp:"label"}))),(0,l.createElement)(a.A,{xs:24,md:12},(0,l.createElement)(r.A.Item,{name:"discount_scope",initialValue:"total",label:(0,l.createElement)(l.Fragment,null,(0,o.__)("Discount applies to","wp-event-solution")," ",(0,l.createElement)(d.ID,{title:(0,o.__)("Ticket price only = discount just the ticket price (the selected tickets above, if any). Entire order total = discount the whole order including add-on options, even when specific tickets are selected.","wp-event-solution")}))},(0,l.createElement)(p.A,{size:"large",options:[{label:(0,o.__)("Entire order total (tickets + add-ons)","wp-event-solution"),value:"total"},{label:(0,o.__)("Ticket price only","wp-event-solution"),value:"tickets"}]})))),(0,l.createElement)(E,{title:(0,o.__)("Usage Limits","wp-event-solution"),description:(0,o.__)("Control how many times and under what conditions the coupon works.","wp-event-solution")}),(0,l.createElement)(c.A,{gutter:[16,0]},(0,l.createElement)(a.A,{xs:24,md:6},(0,l.createElement)(r.A.Item,{name:"usage_limit",label:(0,l.createElement)(l.Fragment,null,(0,o.__)("Total usage limit","wp-event-solution")," ",(0,l.createElement)(d.ID,{title:(0,o.__)("Max redemptions overall. Blank = unlimited.","wp-event-solution")}))},(0,l.createElement)(s.A,{size:"large",style:{width:"100%"},min:1,placeholder:(0,o.__)("Unlimited","wp-event-solution")}))),(0,l.createElement)(a.A,{xs:24,md:6},(0,l.createElement)(r.A.Item,{name:"per_user_limit",label:(0,l.createElement)(l.Fragment,null,(0,o.__)("Per-user limit","wp-event-solution")," ",(0,l.createElement)(d.ID,{title:(0,o.__)("Max redemptions per buyer. Blank = unlimited.","wp-event-solution")}))},(0,l.createElement)(s.A,{size:"large",style:{width:"100%"},min:1,placeholder:(0,o.__)("Unlimited","wp-event-solution")}))),(0,l.createElement)(a.A,{xs:24,md:6},(0,l.createElement)(r.A.Item,{name:"min_purchase",label:(0,o.__)("Min purchase","wp-event-solution")},(0,l.createElement)(s.A,{size:"large",style:{width:"100%"},min:0,placeholder:(0,o.__)("None","wp-event-solution")}))),(0,l.createElement)(a.A,{xs:24,md:6},(0,l.createElement)(r.A.Item,{name:"min_qty",label:(0,o.__)("Min tickets","wp-event-solution")},(0,l.createElement)(s.A,{size:"large",style:{width:"100%"},min:1,placeholder:(0,o.__)("None","wp-event-solution")}))))))}n.d(t,["b2",0,()=>(0,l.createElement)(g.t_,{title:(0,l.createElement)(w,{title:(0,o.__)("Status","wp-event-solution"),description:(0,o.__)("Turn this coupon on or off for checkout.","wp-event-solution")})},(0,l.createElement)(c.A,{justify:"space-between",align:"middle",style:{margin:0}},(0,l.createElement)(a.A,null,(0,o.__)("Active","wp-event-solution")),(0,l.createElement)(a.A,null,(0,l.createElement)(r.A.Item,{name:"active",valuePropName:"checked",initialValue:!0,noStyle:!0},(0,l.createElement)(u.A,null)))))])},8775(e,t,n){var l=n(51609),o=n(27723),i=n(29491),a=n(47143),r=n(86087),s=n(52619),c=n(60742),p=n(428),m=n(74353),u=n.n(m),d=n(26557),_=n(47767),v=n(7638),h=n(64282),g=n(68949),x=n(64861),w=n(59673),E=n(71994);const f={height:"36px",width:"36px",backgroundColor:"var(--etn-bg-subtle, #fafafa)",borderColor:"transparent",lineHeight:"1"},A=(0,a.withSelect)(e=>({eventList:e("eventin/global").getAllEvents()})),y=(0,a.withDispatch)(e=>{const t=e(x.e);return{refreshCouponsList:()=>t.invalidateResolution("getCouponsList")}}),b=(0,i.compose)([A,y])(function(e){const{eventList:t,refreshCouponsList:n}=e,[i]=c.A.useForm(),{id:a}=(0,_.g)(),m=(0,_.Zp)(),x=!!a,[A,y]=(0,r.useState)(!1),[b,C]=(0,r.useState)(!1);return(0,r.useEffect)(()=>{if(!a)return void i.resetFields();let e=!1;return C(!0),h.A.coupons.singleCoupon(a).then(t=>{if(!e&&t){const e=t.start_date?u()(t.start_date):null,n=t.end_date?u()(t.end_date):null;i.setFieldsValue({...t,event_range_name:e&&n?[e,n]:void 0,start_time:t.start_time||(e?e.format("hh:mm A"):void 0),end_time:t.end_time||(n?n.format("hh:mm A"):void 0),active:"inactive"!==t.status})}}).catch(()=>{e||(0,s.doAction)("eventin_notification",{type:"error",message:(0,o.__)("Could not load the coupon.","wp-event-solution")})}).finally(()=>{e||C(!1)}),()=>{e=!0}},[a]),(0,l.createElement)(c.A,{layout:"vertical",form:i,size:"large",scrollToFirstError:!0,onFinish:async()=>{y(!0);try{await i.validateFields();const e=i.getFieldsValue(!0),{start_date:t,end_date:l,start_time:r,end_time:c}=(0,g.V)(e),p={...e,start_date:t||null,end_date:l||null,start_time:r||null,end_time:c||null};delete p.event_range_name;const u=x?await h.A.coupons.updateCoupon(a,p):await h.A.coupons.createCoupon(p);if(!u?.id)throw new Error((0,o.__)("Could not save the coupon.","wp-event-solution"));n(),m("/coupons"),(0,s.doAction)("eventin_notification",{type:"success",message:x?(0,o.__)("Coupon updated successfully!","wp-event-solution"):(0,o.__)("Coupon created successfully!","wp-event-solution")})}catch(e){"duplicate"===e?.code?i.setFields([{name:"code",errors:[(0,o.__)("This coupon code already exists.","wp-event-solution")]}]):e?.errorFields||(0,s.doAction)("eventin_notification",{type:"error",message:e.message||(0,o.__)("Something went wrong.","wp-event-solution")})}finally{y(!1)}},className:"eventin-coupon-form"},(0,l.createElement)(E.Wn,null,(0,l.createElement)(E.Bm,null,(0,l.createElement)(v.Ay,{variant:v.Vt,htmlType:"button",icon:(0,l.createElement)(d.A,null),sx:f,onClick:()=>m("/coupons")}),(0,l.createElement)("h1",null,x?(0,o.__)("Edit Coupon","wp-event-solution"):(0,o.__)("New Coupon","wp-event-solution"))),(0,l.createElement)(E.lX,null,(0,l.createElement)(v.Ay,{variant:v.Vt,htmlType:"button",onClick:()=>m("/coupons")},(0,o.__)("Cancel","wp-event-solution")),(0,l.createElement)(v.Ay,{variant:v.zB,htmlType:"submit",loading:A},x?(0,o.__)("Update Coupon","wp-event-solution"):(0,o.__)("Create Coupon","wp-event-solution")))),b?(0,l.createElement)("div",{style:{padding:"60px 0",textAlign:"center"}},(0,l.createElement)(p.A,null)):(0,l.createElement)(E.rA,null,(0,l.createElement)(E.qH,null,(0,l.createElement)(w.Ay,{form:i,eventList:t})),(0,l.createElement)(E.GY,null,(0,l.createElement)(w.b2,null),(0,l.createElement)(E.yg,null,(0,l.createElement)("h4",null,(0,o.__)("Tips","wp-event-solution")),(0,l.createElement)("ul",null,(0,l.createElement)("li",null,(0,l.createElement)("span",{className:"dot"}),(0,o.__)("Leave events empty to apply the coupon to every event.","wp-event-solution")),(0,l.createElement)("li",null,(0,l.createElement)("span",{className:"dot"}),(0,o.__)("Leave the validity dates empty for no time limit.","wp-event-solution")),(0,l.createElement)("li",null,(0,l.createElement)("span",{className:"dot"}),(0,o.__)("“Apply only to these tickets” discounts just those tickets.","wp-event-solution")),(0,l.createElement)("li",null,(0,l.createElement)("span",{className:"dot"}),(0,o.__)("Native coupons are disabled when WooCommerce is your ticket payment method.","wp-event-solution")))))))});n.d(t,["A",0,b])},35158(e,t,n){n.r(t),n.d(t,{default:()=>r});var l=n(51609),o=n(75093),i=n(8775),a=n(71994);function r(){return(0,l.createElement)(a.ff,null,(0,l.createElement)(i.A,null),(0,l.createElement)(o._W,null))}},71994(e,t,n){var l=n(69815);const o=l.A.div`
	background: var(--etn-bg-page, #f3f5f7);
	min-height: 100vh;
`,i=l.A.div`
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 10px 16px;
	flex-wrap: wrap;
	background-color: var(--etn-surface, #ffffff);
	border-bottom: 1px solid var(--etn-border, #cbd8ea);
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
`,a=l.A.div`
	display: flex;
	align-items: center;
	gap: 12px;
	flex: 1 1 auto;
	min-width: 0;

	h1 {
		font-size: 20px;
		font-weight: 500;
		color: var(--etn-text-secondary, #373360);
		margin: 0;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
`,r=l.A.div`
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
		border: 1px solid var(--etn-border, rgba( 0, 0, 0, 0.3 )) !important;
		border-right: 0 !important;
		border-radius: 5px 0 0 5px !important;
	}

	/* Field labels aligned to the event-form token (16px / 500 / #373360). */
	.ant-form-item-label > label {
		font-size: 15px;
		color: var(--etn-text-secondary, #373360);
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
`,p=l.A.div`
	display: flex;
	flex-direction: column;
	gap: 20px;
`,m=l.A.div`
	background: var(--etn-bg-subtle, #f7f8fa);
	border: 1px solid var(--etn-border, #cbd8ea);
	border-radius: 8px;
	padding: 20px;

	h4 {
		font-size: 15px;
		font-weight: 600;
		color: var(--etn-text, #0b1420);
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
		color: var(--etn-text-muted, #5c728d);
	}

	li .dot {
		flex: 0 0 auto;
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: #6b2ee5;
		margin-top: 6px;
	}
`;n.d(t,["Bm",0,a,"GY",0,p,"Wn",0,i,"ff",0,o,"lX",0,r,"qH",0,c,"rA",0,s,"yg",0,m])},64122(e,t,n){n.d(t,{$o:()=>r,Ae:()=>s,bA:()=>a,f3:()=>i,ix:()=>c});var l=n(27723),o=n(18537);function i(e){return e?"percentage"===e.discount_type?`${e.discount_value}%`:`${e.discount_value}`:""}function a(e=8){let t="";for(let n=0;n<e;n++)t+="ABCDEFGHJKLMNPQRSTUVWXYZ23456789"[Math.floor(32*Math.random())];return t}function r(e){const t=e?.restricted_events?.length||0;return 0===t?(0,l.__)("All events","wp-event-solution"):`${t} ${1===t?(0,l.__)("event","wp-event-solution"):(0,l.__)("events","wp-event-solution")}`}function s(e){var t;const n=null!==(t=e?.usage_count)&&void 0!==t?t:0;return null!=e?.usage_limit?`${n} / ${e.usage_limit}`:`${n} / ∞`}function c(e=[],t=[]){const n=(Array.isArray(t)?t:t?.items||[]).filter(t=>e.includes(Number(t.id))),l=[];return n.forEach(e=>{(e?.ticket_variations||[]).forEach(t=>{const n=t?.etn_ticket_slug,i=t?.etn_ticket_name;n&&l.push({label:`${(0,o.decodeEntities)(e.title)} — ${i||n}`,value:n})})}),l}}}]);