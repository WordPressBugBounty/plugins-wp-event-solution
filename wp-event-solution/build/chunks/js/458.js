"use strict";(self.webpackChunkwp_event_solution=self.webpackChunkwp_event_solution||[]).push([[458],{2959:(e,t,a)=>{a.d(t,{A:()=>A});var n=a(51609),l=a(27723),r=a(17437),i=a(67313),s=a(60742),d=a(36492),c=a(51643),o=a(38181),m=a(54861),u=a(74353),_=a.n(u),p=a(5019),v=a(10012);const{Text:g,Title:E}=i.A,A=function(e){const{attendeeExtraFields:t,parentKey:a}=e;return(0,n.createElement)("div",{className:"etn-extra-fields-container"},(0,n.createElement)(r.mL,{styles:r.AH`
					.etn-extra-form-field {
						.ant-form-item-extra {
							font-size: 14px;
							font-style: italic;
							margin-bottom: 10px;
							letter-spacing: 0.5px;
						}
					}
				`}),t?.map(((e,t,r)=>(0,n.createElement)("div",{className:"etn-extra-form-field",key:t},function(e){const t=e?.label?.toLowerCase()?.replace(/\s+/g,"_"),r=a?["attendees",a,"extra_fields",t]:["extra_fields",t];switch(e?.field_type){case"text":return(0,n.createElement)(v.ks,{label:e?.label,name:r,placeholder:(0,l.__)(`${e?.placeholder_text||""}`,"eventin"),size:"large",type:"text",required:e?.required,extra:e?.additional_text,rules:[{required:e?.required,message:(0,l.__)(`${e?.label} is required!`,"eventin")}]});case"textarea":return(0,n.createElement)(v.No,{label:e?.label,name:r,placeholder:e?.placeholder_text||"",type:"textarea",extra:e?.additional_text,rows:3,cols:50,required:e?.required,className:"etn-extra-field-text-area",rules:[{required:e?.required,message:(0,l.__)(`${e?.label} is required!`,"eventin")}]});case"number":return(0,n.createElement)(s.A.Item,{label:e?.label,name:r,placeholder:e?.placeholder_text||"",extra:e?.additional_text,rules:[{required:e?.required,message:(0,l.__)(`${e?.label} is required!`,"eventin")}],required:e?.required},(0,n.createElement)(p.A,{placeholder:e?.placeholder_text||"",className:"etn-extra-field-number"}));case"select":return(0,n.createElement)(s.A.Item,{label:e?.label,name:r,extra:e?.additional_text,rules:[{required:e?.required,message:(0,l.__)(`${e?.label} is required!`,"eventin")}],required:e?.required},(0,n.createElement)(d.A,{placeholder:e?.placeholder_text||"",size:"large",options:e?.field_options,allowClear:!0,className:"etn-extra-field-select"}));case"radio":return(0,n.createElement)(s.A.Item,{label:e?.label,name:r,extra:e?.additional_text,rules:[{required:e?.required,message:(0,l.__)(`${e?.label} is required!`,"eventin")}]},(0,n.createElement)(c.Ay.Group,{className:"etn-radio-group"},e?.field_options?e?.field_options?.map(((e,t)=>(0,n.createElement)(c.Ay,{key:t,value:e.value},e.value))):null));case"checkbox":return(0,n.createElement)(s.A.Item,{label:e?.label,name:r,extra:e?.additional_text,rules:[{required:e?.required,message:(0,l.__)(`${e?.label} is required!`,"eventin")}]},(0,n.createElement)(o.A.Group,{className:"etn-checkbox-group"},e?.field_options?.map(((e,t)=>(0,n.createElement)(o.A,{key:t,value:e?.value},e?.value)))));case"date":return(0,n.createElement)(s.A.Item,{label:e?.label,name:r,getValueProps:e=>({value:e?_()(e):null}),normalize:e=>e?_()(e).format("YYYY-MM-DD"):e,extra:e?.additional_text,rules:[{required:e?.required,message:(0,l.__)(`${e?.label} is required!`,"eventin")}]},(0,n.createElement)(m.A,{size:"large",style:{width:"100%"}}));default:return null}}(e)))))}},89833:(e,t,a)=>{a.d(t,{A:()=>s});var n=a(51609),l=a(82654),r=a(69815);a(7638);const i=r.default.div`
	.ant-alert-message {
		font-size: 14px;
		color: #020617;
		font-weight: 500;
	}
	.ant-alert-description {
		font-size: 12px;
		color: #747474;
	}
`,s=(r.default.div`
	@media only screen and ( max-width: 520px ) {
		display: none;
	}
`,e=>{const{title:t,description:a=null}=e;return(0,n.createElement)(i,null,(0,n.createElement)(l.A,{style:{border:"1px solid #FF4D4F",backgroundColor:"#FFFAFA",marginTop:"20px"},message:t,description:a,type:"warning"}))})},82833:(e,t,a)=>{a.d(t,{A:()=>u});var n=a(51609),l=a(27723),r=a(86087),i=a(60742),s=a(47152),d=a(16370),c=a(36492),o=a(10012),m=a(2959);const u=e=>{const{form:t,eventList:a,attendeeExtraFields:u,isInEditMode:_,isPhoneRequired:p,isEmailRequired:v,defaultExtraFields:g,setIsSeatPlanEvent:E}=e,[A,x]=(0,r.useState)(null),[f,h]=(0,r.useState)(u),b=i.A.useWatch("etn_event_id",{form:t,preserve:!0}),y=i.A.useWatch("attendee_seat",{form:t,preserve:!0}),q=a&&a?.items.map((e=>({value:e.id,label:e.title}))),k=A?.ticket_variations&&A?.ticket_variations?.map((e=>({value:e?.etn_ticket_name,label:e?.etn_ticket_name}))),w=Boolean(A?.seat_plan);return E(!(_||!w)),(0,r.useEffect)((()=>{b&&a?.items?.map((e=>{e.id==b&&(x(e),e?.extra_fields.length>0?h(e?.extra_fields):h(u))}))}),[b]),(0,n.createElement)(s.A,{gutter:[16,0]},!_&&(0,n.createElement)(d.A,{xs:24,md:24},(0,n.createElement)(i.A.Item,{label:(0,l.__)("Select Event","eventin"),name:"etn_event_id",rules:[{required:!0,message:(0,l.__)("Please select event","eventin")}]},(0,n.createElement)(c.A,{options:q,showSearch:!0,optionFilterProp:"label",size:"large",placeholder:(0,l.__)("Select Event","eventin"),allowClear:!0,onChange:()=>t.setFieldsValue({ticket_name:null,etn_ticket_price:null})}))),Array.isArray(g)?(0,n.createElement)(n.Fragment,null,g?.map((e=>{if(e?.show)return(0,n.createElement)(d.A,{xs:24,md:24},(0,n.createElement)(o.ks,{key:e.name,label:e.label,name:e.name,rules:[{required:e.required,message:e.label+(0,l.__)(" is required!","eventin")},"etn_phone"===e.name&&{pattern:new RegExp(/^[+]?[\d\s()-]+$/),message:(0,l.__)("Please enter a valid phone number","eventin")}].filter(Boolean),required:e.required,placeholder:e.placeholder_text,size:"large"}))}))):(0,n.createElement)(n.Fragment,null,(0,n.createElement)(d.A,{xs:24,md:24},(0,n.createElement)(o.ks,{label:(0,l.__)("Full name","eventin"),name:"etn_name",rules:[{required:!0,message:(0,l.__)("Full name is required!","eventin")}],required:!0,placeholder:(0,l.__)("Name of the attendee","eventin"),size:"large"})),v&&(0,n.createElement)(d.A,{xs:24,md:12},(0,n.createElement)(o.ks,{label:(0,l.__)("Email","eventin"),name:"etn_email",placeholder:(0,l.__)("Enter your email","eventin"),size:"large",rules:[{type:"email",required:!0,message:(0,l.__)("Enter Valid Email!","eventin")}],required:!0})),p&&(0,n.createElement)(d.A,{xs:24,md:12},(0,n.createElement)(o.ks,{label:(0,l.__)("Phone","eventin"),name:"etn_phone",placeholder:(0,l.__)("+01234567490","eventin"),rules:[{required:!0,message:(0,l.__)("Phone is Required!","eventin")},{pattern:new RegExp(/^[+]?[\d\s()-]+$/),message:(0,l.__)("Please enter a valid phone number","eventin")}],required:!0}))),_&&(0,n.createElement)(d.A,{xs:24,md:12},(0,n.createElement)(o.ks,{label:(0,l.__)("Ticket ID","eventin"),name:"etn_unique_ticket_id",size:"large",readOnly:!0,disabled:!0})),(0,n.createElement)(d.A,{xs:24,md:12},_?(0,n.createElement)(o.ks,{label:y?(0,l.__)("Seat Name","eventin"):(0,l.__)("Ticket Name","eventin"),name:y?"attendee_seat":"ticket_name",size:"large",readOnly:!0,disabled:!0}):(0,n.createElement)(i.A.Item,{label:(0,l.__)("Ticket Name","eventin"),name:"ticket_name"},(0,n.createElement)(c.A,{options:k,size:"large",showSearch:!0,optionFilterProp:"label",placeholder:(0,l.__)("Select a Ticket"),onSelect:e=>{A?.ticket_variations?.map((a=>{a.etn_ticket_name===e&&t.setFieldsValue({etn_ticket_price:a.etn_ticket_price,ticket_slug:a.etn_ticket_slug})}))},filterOption:(e,t)=>t?.label?.toLowerCase()?.includes(e?.toLowerCase())}))),!_&&(0,n.createElement)(d.A,{xs:24,md:12},(0,n.createElement)(o.ks,{label:(0,l.__)("Ticket Price","eventin"),name:"etn_ticket_price",size:"large",placeholder:(0,l.__)("Ticket Price","eventin"),readOnly:!0})),(0,n.createElement)(d.A,{xs:24,md:12},(0,n.createElement)(i.A.Item,{label:(0,l.__)("Ticket Status","eventin"),name:"etn_attendeee_ticket_status"},(0,n.createElement)(c.A,{options:[{label:(0,l.__)("Unused","eventin"),value:"unused"},{label:(0,l.__)("Used","eventin"),value:"used"}],size:"large",placeholder:(0,l.__)("Select Ticket Status","eventin")}))),(0,n.createElement)(d.A,{xs:24,md:12},(0,n.createElement)(i.A.Item,{label:(0,l.__)("Payment Status","eventin"),name:"etn_status"},(0,n.createElement)(c.A,{options:[{label:(0,l.__)("Success","eventin"),value:"success"},{label:(0,l.__)("Failed","eventin"),value:"failed"}],size:"large",placeholder:(0,l.__)("Select Payment Status","eventin")}))),(0,n.createElement)(d.A,{xs:24,md:24},(0,n.createElement)(m.A,{attendeeExtraFields:f})))}},97439:(e,t,a)=>{a.d(t,{A:()=>q});var n=a(51609),l=a(29491),r=a(47143),i=a(86087),s=a(52619),d=a(27723),c=a(69815),o=a(60742),m=a(47152),u=a(16370),_=a(92911),p=a(428),v=a(47767),g=a(7638),E=a(57237),A=a(64282),x=a(82833),f=a(89833);const h=c.default.div`
	background-color: #ffffff;
	border: 1px solid #e1e4e9;
	border-radius: 8px;
	padding: 20px 40px;
	margin-top: 40px;
`,b=(0,r.withDispatch)((e=>{const t=e("eventin/global");return{refreshAttendeesList:()=>t.invalidateResolution("getAttendeesList")}})),y=(0,r.withSelect)((e=>{const t=e("eventin/global");return{settings:t.getSettings(),isSettingsLoading:t.isResolving("getSettings"),eventList:t.getEventList(),isLoading:t.isResolving("getEventList"),refreshAttendeesList:()=>t.invalidateResolution("getAttendeesList")}})),q=(0,l.compose)([y,b])((function(e){const{isLoading:t,isSettingsLoading:a,settings:l,eventList:r,refreshAttendeesList:c}=e,[b]=o.A.useForm(),{id:y}=(0,v.useParams)(),q=(0,v.useNavigate)(),[k,w]=(0,i.useState)(!1),[F,S]=(0,i.useState)(!1),z=l&&l?.extra_fields,L=l&&"on"===l?.reg_require_phone,N=l&&"on"===l?.reg_require_email,P=l&&l?.default_extra_fields,C=!!y;(0,i.useEffect)((()=>{y&&!k&&(w(!0),A.A.attendees.singleAttendee(y).then((e=>{let t={...e,extra_fields:Array.isArray(e?.extra_fields)?{}:e?.extra_fields};b.setFieldsValue(t)})).finally((()=>{w(!1)})))}),[]);const T=k||t||a;return(0,n.createElement)(m.A,{gutter:[16,10],justify:"center",style:{margin:"20px 0px"},className:"eventin-edit-attendee-form-wrapper"},(0,n.createElement)(u.A,{xs:22,md:14,className:"eventin-edit-attendee-form-container"},(0,n.createElement)(E.A,{level:3,style:{fontSize:"24px",height:"32px"}},C?(0,d.__)("Update Attendee","eventin"):(0,d.__)("Create New Attendee","eventin")),(0,n.createElement)(h,null,T?(0,n.createElement)(_.A,{justify:"center",align:"center",style:{minHeight:"320px"}},(0,n.createElement)(p.A,null)):(0,n.createElement)(o.A,{layout:"vertical",form:b,scrollToFirstError:!0,size:"large",onFinish:async()=>{w(!0);try{await b.validateFields();const e=b.getFieldsValue(!0);if(C){const t=await A.A.attendees.updateAttendee(y,e);if(!t?.id)throw new Error("Couldn't update attendee properly!");q("/attendees"),c(),(0,s.doAction)("eventin_notification",{type:"success",message:(0,d.__)("Successfully updated the attendee!","eventin")})}else{const t=await A.A.attendees.createAttendee(e);if(!t?.id)throw new Error("Couldn't create attendee properly!");c(),q("/attendees"),(0,s.doAction)("eventin_notification",{type:"success",message:(0,d.__)("Successfully created the attendee!","eventin")})}}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{w(!1)}}},(0,n.createElement)(x.A,{form:b,eventList:r,attendeeExtraFields:z,isInEditMode:C,isPhoneRequired:L,isEmailRequired:N,defaultExtraFields:P,setIsSeatPlanEvent:S}),F&&(0,n.createElement)(f.A,{title:(0,d.__)("You can't add an attendee to a seat plan event ","eventin"),description:(0,d.__)("This is a seat plan event. You can only add attendees to a non-seat plan event","eventin")}),(0,n.createElement)(_.A,{gap:16,style:{marginTop:20}},(0,n.createElement)(g.Ay,{variant:g.Rm,htmlType:"reset",onClick:()=>q(-1)},(0,d.__)("Cancel","eventin")),(0,n.createElement)(g.Ay,{variant:g.zB,loading:k,htmlType:"submit",disabled:F},C?(0,d.__)("Update Attendee","eventin"):(0,d.__)("Add Attendee","eventin")))))))}))},14458:(e,t,a)=>{a.r(t),a.d(t,{default:()=>_});var n=a(51609),l=a(56427),r=a(92911),i=a(47767),s=a(54725),d=a(7638),c=a(79664),o=a(18062),m=a(27154),u=a(97439);const _=function(e){const{id:t}=(0,i.useParams)(),a=!!t,_=(0,i.useNavigate)();return(0,n.createElement)("div",{style:{backgroundColor:"#F3F5F7",marginTop:"-20px",paddingBottom:"20px",minHeight:"100vh"}},(0,n.createElement)(l.Fill,{name:m.PRIMARY_HEADER_NAME},(0,n.createElement)(r.A,{justify:"space-between",align:"center"},(0,n.createElement)(r.A,{align:"center",gap:16},(0,n.createElement)(d.Ay,{variant:d.Vt,icon:(0,n.createElement)(s.AngleLeftIcon,null),sx:{height:"36px",width:"36px",backgroundColor:"#fafafa",borderColor:"transparent",lineHeight:"1"},onClick:()=>{_("/attendees")}}),(0,n.createElement)(o.A,{title:a?"Attendees Details":"New Attendee"})),(0,n.createElement)(c.A,null))),(0,n.createElement)(u.A,null))}}}]);