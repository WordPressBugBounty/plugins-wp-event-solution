"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[5761],{40728(e,t,a){var n=a(51609),r=a(27723),o=a(50400),i=a(89500),l=a(36492),d=a(99150),c=a(72121),p=a(99489);a.d(t,["A",0,({total:e=0,currentPage:t=1,pageSize:a=10,onPageChange:s,onPageSizeChange:g,pageSizeOptions:f=["5","10","20","50","100"],wrapperClassName:u="eventin-pagination-wrapper"})=>{const m=0===e?0:(t-1)*a+1,b=Math.min(t*a,e),x=e=>{s&&s(e)};return(0,n.createElement)(p.C,{className:u},(0,n.createElement)("div",{className:"pagination-left"},(0,n.createElement)("span",{className:"rows-per-page-label"},(0,r.__)("Rows per page:","eventin")),(0,n.createElement)(l.A,{value:a.toString(),onChange:e=>{g&&g(e)},options:f.map(e=>({value:e,label:e})),size:"middle"})),(0,n.createElement)("div",{className:"pagination-right"},(0,n.createElement)("span",{className:"pagination-info"},m,"-",b," ",(0,r.__)("of","eventin")," ",e),(0,n.createElement)(i.A,{current:t,total:e,pageSize:a,onChange:x,showSizeChanger:!1,showQuickJumper:!1,showTotal:!1,prevIcon:(0,n.createElement)(o.A,{icon:(0,n.createElement)(d.A,null),iconPosition:"start",variant:"outlined",onClick:()=>x(t-1),disabled:1===t,style:{height:"100%"}},(0,r.__)("Previous","eventin")),nextIcon:(0,n.createElement)(o.A,{icon:(0,n.createElement)(c.A,null),iconPosition:"end",variant:"outlined",onClick:()=>x(t+1),disabled:t===e,style:{height:"100%"}},(0,r.__)("Next","eventin")),simple:!1})))}])},99489(e,t,a){const n=a(69815).A.div`
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 16px;

	.pagination-left {
		display: flex;
		align-items: center;
		gap: 8px;
		color: var(--etn-text-muted, #71717a);
		font-size: 14px;

		.rows-per-page-label {
			white-space: nowrap;
			font-weight: 400;
		}

		.ant-select {
			min-width: 70px;

			.ant-select-selector {
				border-color: var(--etn-border-subtle, #e4e4e7);
				border-radius: 6px;
			}
		}
	}

	.pagination-right {
		display: flex;
		align-items: center;
		gap: 24px;

		.pagination-info {
			color: var(--etn-text-muted, #71717a);
			font-size: 14px;
			font-weight: 400;
		}

		.ant-pagination {
			display: flex;
			align-items: center;
			gap: 8px !important;
			margin: 0;

			li {
				margin-inline: 0px !important;
			}

			.ant-pagination-prev,
			.ant-pagination-next {
				min-width: auto;
				height: 36px;
				color: var(--etn-text-secondary, #4b4b4b);
				font-size: 14px;
				font-weight: 500;
				.ant-pagination-item-link {
					border: 1px solid var(--etn-border, #d4d4d8);
					border-radius: 4px;
					background-color: transparent;
					display: flex;
					align-items: center;
					justify-content: center;
					color: var(--etn-text-muted, #71717a);
					font-size: 13px;
					padding: 0 12px;
					height: 36px;
					font-weight: 400;

					&:hover {
						border-color: var(--etn-border, #a1a1aa);
						color: var(--etn-text-secondary, #52525b);
						background-color: transparent;
					}
				}

				&.ant-pagination-disabled {
					.ant-pagination-item-link {
						border-color: var(--etn-border-subtle, #e4e4e7);
						color: var(--etn-text-disabled, #d4d4d8);
						background-color: transparent;
						cursor: not-allowed;

						&:hover {
							border-color: var(--etn-border-subtle, #e4e4e7);
							color: var(--etn-text-disabled, #d4d4d8);
							background-color: transparent;
						}
					}
				}
			}

			.ant-pagination-item {
				border: 1px solid var(--etn-border, #d9dde3);
				border-radius: 4px;
				min-width: 36px;
				height: 36px;
				display: flex;
				align-items: center;
				justify-content: center;
				font-size: 13px;
				background-color: var(--etn-surface, white);
				line-height: 34px;

				a {
					color: var(--etn-text-muted, #71717a);
					font-weight: 400;
				}

				&:hover {
					border-color: var(--etn-primary-border, #f2e8ff);
					background-color: var(--etn-primary-bg, #f2e8ff);

					a {
						color: var(--etn-text-secondary, #52525b);
					}
				}

				&.ant-pagination-item-active {
					background-color: var(--etn-primary-bg, #f2e8ff);
					border-color: var(--etn-primary-border, #f2e8ff);

					a {
						color: var(--etn-text, #18181b);
						font-weight: 500;
					}

					&:hover {
						background-color: var(--etn-primary-bg, #f2e8ff);
						border-color: var(--etn-primary-border, #f2e8ff);

						a {
							color: var(--etn-text, #18181b);
						}
					}
				}
			}
		}
	}

	@media ( max-width: 768px ) {
		flex-direction: column;
		gap: 16px;
		align-items: flex-start;

		.pagination-right {
			width: 100%;
			flex-direction: column;
			align-items: flex-start;
			gap: 12px;
		}
	}
`;a.d(t,["C",0,n])},16017(e,t,a){var n=a(69815),r=a(36492);const o=n.A.div`
	background-color: var(--etn-bg-page, #f4f6fa);
	padding: 12px 32px;
	min-height: 100vh;

	.ant-table-wrapper {
		padding: 15px;
		background-color: var(--etn-surface, #fff);
		border-radius: 12px;
	}

	.event-list-wrapper {
		border-radius: 12px;
	}

	.ant-table-thead {
		> tr {
			> th {
				background-color: var(--etn-surface, #ffffff);
				padding-top: 10px;
				font-weight: 400;
				color: var(--etn-text-muted, #7a7a99);
				font-size: 16px;
				&:before {
					display: none;
				}
			}
		}
	}

	tr {
		&:hover {
			background-color: var(--etn-bg-subtle, #f8fafc) !important;
		}
	}

	.coupon-code {
		color: var(--etn-text, #262626);
		font-size: 16px;
		font-weight: 600;
	}

	.event-actions {
		.anticon {
			font-size: 16px;
			color: var(--etn-text-secondary, #525266);
		}
	}
`,i=(0,n.A)(r.A)`
	min-width: 180px;

	.ant-select-selector {
		height: 36px !important;
		border-radius: 8px;
		border: 1px solid var(--etn-border-subtle, #e5e7eb);
		background-color: var(--etn-surface, #fff);
		color: var(--etn-text-secondary, #334155);
		font-size: 14px;
	}
`;a.d(t,["C",0,i,"f",0,o])},85761(e,t,a){a.r(t),a.d(t,{default:()=>v});var n=a(51609),r=a(27723),o=a(56427),i=a(86087),l=a(92911),d=a(47767),c=a(26557),p=a(7638),s=a(18062),g=a(75093),f=a(40728),u=a(27154),m=a(64282),b=a(16017);const x=[{title:(0,r.__)("Buyer","eventin"),dataIndex:"buyer_email",key:"buyer_email"},{title:(0,r.__)("Order","eventin"),dataIndex:"order_id",key:"order_id",render:e=>`#${e}`},{title:(0,r.__)("Date","eventin"),dataIndex:"date",key:"date"},{title:(0,r.__)("Discount","eventin"),dataIndex:"discount_amount",key:"discount_amount",render:e=>`${e}`}];function v(){const{id:e}=(0,d.g)(),t=(0,d.Zp)(),[a,v]=(0,i.useState)({items:[],total_items:0}),[h,w]=(0,i.useState)(!0),[y,_]=(0,i.useState)(""),[k,E]=(0,i.useState)(u.X$.paged),[C,A]=(0,i.useState)(u.X$.per_page);return(0,i.useEffect)(()=>{m.A.coupons.singleCoupon(e).then(e=>_(e?.code||"")).catch(()=>_(""))},[e]),(0,i.useEffect)(()=>{w(!0),m.A.coupons.redemptions(e,{paged:k,per_page:C}).then(v).finally(()=>w(!1))},[e,k,C]),(0,n.createElement)(b.f,{className:"eventin-page-wrapper"},(0,n.createElement)(o.Fill,{name:u.PQ},(0,n.createElement)(l.A,{align:"center",gap:16},(0,n.createElement)(p.Ay,{variant:p.Vt,icon:(0,n.createElement)(c.A,null),sx:{height:"36px",width:"36px",backgroundColor:"var(--etn-bg-subtle, #fafafa)",borderColor:"transparent",lineHeight:"1"},onClick:()=>t("/coupons")}),(0,n.createElement)(s.A,{title:`${(0,r.__)("Coupon Usage","eventin")}: ${y}`}))),(0,n.createElement)("div",{className:"event-list-wrapper"},(0,n.createElement)(g.Ee,{columns:x,dataSource:a.items,loading:h,rowKey:e=>`${e.order_id}`,showPagination:!1}),(0,n.createElement)(f.A,{total:a.total_items,currentPage:k,pageSize:C,onPageChange:e=>E(Number(e)),onPageSizeChange:e=>{A(Number(e)),E(1)}})))}}}]);