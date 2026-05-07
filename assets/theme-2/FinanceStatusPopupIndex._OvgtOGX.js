import {
  m as O,
  e as H,
  nf as D,
  gV as I,
  hk as V,
  dh as $,
  ng as l,
  O as N,
  gZ as B,
  h5 as _,
  d,
  kt as E,
  aA as k,
  af as P,
  a0 as L,
  nh as U,
  ni as W,
  A as Y,
  cs as q,
  a2 as z,
  nj as M,
  F as Z,
  h as J,
} from "./commonChunk.CVzQPmoh.js";
import { f as G } from "../vendors/vendor-.CBcVDtao.js";
import {
  d as j,
  c as m,
  a as e,
  t as x,
} from "../vendors/vendor-stable.BbCbSU7n.js";
import "../vendors/vendor-bignumber.HVYCcly-.js";
import "./vendorChunk.D7TmZIHS.js";
import "../vendors/vendor-swiper.CGEhtrpu.js";
import "../vendors/vendor-@sentry.6viaZao7.js";
const [K] = O("pages-dialogs-fiance-pay-content"),
  Q = j({
    name: K,
    props: { data: { type: Object, default: () => {} } },
    setup(a) {
      const { t: c } = H(),
        { statusClassName: p } = D(),
        T = () => {
          var n, s;
          const t =
            (n = a.data) != null && n.status
              ? _[(s = a.data) == null ? void 0 : s.status]
              : void 0;
          return t != null ? t : _[I.wait];
        },
        g = m(() => {
          var t;
          return p((t = a.data) == null ? void 0 : t.status);
        }),
        h = () => {
          var t, n, s;
          return d(
            (n = (t = a.data) == null ? void 0 : t.realAmount) != null
              ? n
              : "0",
            {
              code: (s = a.data) == null ? void 0 : s.memberCurrency,
              sign: !1,
              toFixedType: "score",
            }
          ).toString();
        },
        v = () => {
          var t, n, s;
          return d(
            (n = (t = a.data) == null ? void 0 : t.channelAmount) != null
              ? n
              : "0",
            {
              code: (s = a.data) == null ? void 0 : s.payCurrency,
              sign: !1,
              toFixedType: "currency",
            }
          ).toString();
        },
        A = () => {
          var t, n, s, r, i, o, b;
          return (t = a.data) != null && t.exchangeRate
            ? d(
                (r = G((n = a.data) == null ? void 0 : n.giftAmount)
                  .div((s = a.data) == null ? void 0 : s.exchangeRate)
                  .toString()) != null
                  ? r
                  : "0",
                {
                  toFixedType: "currency",
                  code: (i = a.data) == null ? void 0 : i.payCurrency,
                }
              ).toString()
            : d(
                (b = (o = a.data) == null ? void 0 : o.giftAmount) != null
                  ? b
                  : "0",
                { toFixedType: "currency" }
              ).toString();
        },
        y = m(() => {
          var t;
          return ((t = a.data) == null ? void 0 : t.status) === I.success;
        }),
        S = () => {
          var t;
          return a.data
            ? d((t = a.data.amount) != null ? t : "0", {
                toFixedType: "currency",
              }).toString()
            : "0";
        },
        f = () => {
          var n, s, r, i, o;
          if (!a.data) return "0";
          const t =
            (n = a.data) != null && n.currencyRate
              ? (s = a.data) == null
                ? void 0
                : s.channelAmount
              : Number((r = a.data) == null ? void 0 : r.channelAmount) +
                Number((i = a.data) == null ? void 0 : i.giftAmount);
          return d(t != null ? t : "0", {
            sign: "auto",
            code: (o = a.data) == null ? void 0 : o.payCurrency,
            toFixedType: "currency",
          }).toString();
        },
        C = m(() => {
          var t, n;
          return (
            ((t = a.data) == null ? void 0 : t.icon_url) ||
            V(Number((n = a.data) == null ? void 0 : n.payTypeIcon))
          );
        }),
        u = m(() => {
          var n, s, r;
          const t = $((n = a.data) == null ? void 0 : n.memberCurrency);
          return e("span", { class: l["member-currency"] }, [
            (t == null ? void 0 : t.gameRate) !== 1 &&
            !((s = a.data) != null && s.currencyRate)
              ? t == null
                ? void 0
                : t.currencyDisplay
              : (r = a.data) == null
              ? void 0
              : r.payCurrency,
          ]);
        });
      return () => {
        var t, n, s, r, i, o, b, F, w, R;
        return e(
          "div",
          {
            class: [l["fiance-content"]],
            key: ""
              .concat((t = a.data) == null ? void 0 : t.order_no, "_")
              .concat((n = a.data) == null ? void 0 : n.status),
          },
          [
            e("div", { class: [l["status-icon"], g.value.bg] }, [
              e(N, { src: T() }, null),
            ]),
            e("div", { class: [l["status-text"], g.value.color] }, [
              (s = a.data) == null ? void 0 : s.status_name,
            ]),
            e("ul", null, [
              y.value &&
                e("li", null, [
                  e("span", null, [
                    c("lobby.finance.popupTipsText.payAmountReceived"),
                  ]),
                  e("p", null, [
                    h(),
                    (r = a.data) != null && r.currencyRate
                      ? e("span", { class: l["currency-amount"] }, [
                          x("（"),
                          c("lobby.finance.popupTipsText.approximately"),
                          f(),
                          x("）"),
                        ])
                      : u.value,
                  ]),
                ]),
              !y.value &&
                e("li", null, [
                  e("span", null, [c("lobby.finance.popupTipsText.payAmount")]),
                  e("p", null, [
                    S(),
                    (i = a.data) != null && i.currencyRate
                      ? e("span", { class: l["currency-amount"] }, [
                          x("（"),
                          c("lobby.finance.popupTipsText.approximately"),
                          f(),
                          x("）"),
                        ])
                      : u.value,
                  ]),
                ]),
              y.value &&
                e("li", null, [
                  e("span", null, [
                    c("lobby.finance.popupTipsText.giveawayAmount"),
                  ]),
                  e("p", null, [A(), u.value]),
                ]),
              y.value &&
                e("li", null, [
                  e("span", null, [
                    c("lobby.finance.popupTipsText.receivedPayAmount"),
                  ]),
                  e("p", null, [
                    v(),
                    e("span", { class: l["member-currency"] }, [
                      ((o = a.data) == null ? void 0 : o.pay_kind) ===
                      B.merchant
                        ? u.value
                        : (b = a.data) == null
                        ? void 0
                        : b.payCurrency,
                    ]),
                  ]),
                ]),
              e("li", null, [
                e("span", null, [c("lobby.finance.popupTipsText.payType")]),
                e("p", { class: l["align-items-center"] }, [
                  e(N, { class: l.logo, src: C.value }, null),
                  e("span", { class: l["type-name"] }, [
                    (F = a.data) == null ? void 0 : F.payment_name,
                  ]),
                ]),
              ]),
              !y.value &&
                ((w = a.data) == null ? void 0 : w.frontRemark) &&
                e("li", null, [
                  e("span", null, [
                    c("lobby.finance.popupTipsText.cancelIllustrate"),
                  ]),
                  e("p", { class: l.remark }, [
                    (R = a.data) == null ? void 0 : R.frontRemark,
                  ]),
                ]),
            ]),
          ]
        );
      };
    },
  }),
  [X] = O("pages-dialogs-fiance-tips-withdraw"),
  aa = j({
    name: X,
    props: { data: { type: Object, default: () => {} } },
    setup(a) {
      const { t: c } = H(),
        { getColors: p, withdrawStatusIcon: T } = D(),
        g = () => {
          var t, n;
          const u =
            (t = a.data) != null && t.status
              ? T[(n = a.data) == null ? void 0 : n.status]
              : void 0;
          return u != null ? u : T[E.PENDING];
        },
        h = () => {
          var u, t;
          return d(
            (t = (u = a.data) == null ? void 0 : u.money) != null ? t : "0",
            { toFixedType: "score" }
          ).toString();
        },
        v = () => {
          var u, t;
          return d(
            (t = (u = a.data) == null ? void 0 : u.fee) != null ? t : "0",
            { toFixedType: "score" }
          ).toString();
        },
        A = () => {
          var u, t, n;
          return d(
            (t = (u = a.data) == null ? void 0 : u.currencyAmount) != null
              ? t
              : "0",
            {
              code: (n = a.data) == null ? void 0 : n.currencyCode,
              sign: !1,
              toFixedType: "currency",
            }
          ).toString();
        },
        y = () => {
          var t, n, s, r, i;
          const u = G(
            (n = (t = a.data) == null ? void 0 : t.currencyAmount) != null
              ? n
              : "0"
          )
            .plus(
              (r = (s = a.data) == null ? void 0 : s.channelFee) != null
                ? r
                : "0"
            )
            .toString();
          return d(u, {
            code: (i = a.data) == null ? void 0 : i.currencyCode,
            sign: !1,
            toFixedType: "currency",
          }).toString();
        },
        S = m(() => {
          var t, n;
          const u = $((t = a.data) == null ? void 0 : t.currencyCode);
          return e("span", { class: l["member-currency"] }, [
            (u == null ? void 0 : u.currencyDisplay) ||
              ((n = a.data) == null ? void 0 : n.currencyCode),
          ]);
        }),
        f = m(() => {
          var u, t;
          return (
            ((u = a.data) == null ? void 0 : u.status) === E.SUCCESS ||
            ((t = a.data) == null ? void 0 : t.status) === E.ENFORCE
          );
        }),
        C = m(() => {
          var u;
          return e("span", { class: l["currency-code"] }, [
            (u = a.data) == null ? void 0 : u.currencyCode,
          ]);
        });
      return () => {
        var u, t, n, s, r, i, o, b, F, w, R;
        return e("div", { class: [l["fiance-content"]] }, [
          e(
            "div",
            {
              class: [
                l["status-icon"],
                p((u = a.data) == null ? void 0 : u.status)[1],
              ],
            },
            [e(N, { src: g() }, null)]
          ),
          e(
            "div",
            {
              class: [
                l["status-text"],
                p((t = a.data) == null ? void 0 : t.status)[0],
              ],
            },
            [(n = a.data) == null ? void 0 : n.statusText]
          ),
          e("ul", null, [
            e("li", null, [
              e("span", null, [
                k(
                  [
                    [
                      P.enable,
                      c("lobby.club.finance.popupTipsText.withdrawAmount"),
                    ],
                  ],
                  c("lobby.finance.popupTipsText.withdrawAmount")
                ),
              ]),
              e("p", null, [
                h(),
                (s = a.data) != null && s.currencyRate
                  ? e("span", { class: l["currency-amount"] }, [
                      x("（"),
                      c("lobby.finance.popupTipsText.approximately"),
                      y(),
                      (r = a.data) == null ? void 0 : r.currencyCode,
                      x("）"),
                    ])
                  : S.value,
              ]),
            ]),
            f.value &&
              e("li", null, [
                e("span", null, [
                  c("lobby.finance.popupTipsText.withdrawFees"),
                ]),
                e("p", null, [v(), S.value]),
              ]),
            f.value &&
              e("li", null, [
                e("span", null, [
                  c("lobby.finance.popupTipsText.withdrawAmountReceived"),
                ]),
                e("p", null, [A(), C.value]),
              ]),
            e("li", null, [
              e("span", null, [
                k(
                  [
                    [
                      P.enable,
                      c("lobby.club.finance.popupTipsText.withdrawTo"),
                    ],
                  ],
                  c("lobby.finance.popupTipsText.withdrawTo")
                ),
              ]),
              e("p", { class: l["align-items-center"] }, [
                e(
                  N,
                  {
                    class: l.logo,
                    src: (i = a.data) == null ? void 0 : i.logo,
                  },
                  null
                ),
                e("span", { class: l["type-name"] }, [
                  (o = a.data) == null ? void 0 : o.withdrawTypeName,
                  " ",
                  ((b = a.data) == null ? void 0 : b.account) &&
                    e("span", { class: l.noCar }, [
                      c("lobby.finance.withdraw.bankLessNo", {
                        no: (F = a.data) == null ? void 0 : F.account.slice(-4),
                      }),
                    ]),
                ]),
              ]),
            ]),
            !f.value &&
              ((w = a.data) == null ? void 0 : w.remark) &&
              e("li", null, [
                e("span", null, [
                  c("lobby.finance.popupTipsText.cancelIllustrate"),
                ]),
                e("p", { class: l.remark }, [
                  (R = a.data) == null ? void 0 : R.remark,
                ]),
              ]),
          ]),
        ]);
      };
    },
  }),
  [ta] = O("pages-dialogs-fiance-tips"),
  ia = j({
    name: ta,
    setup() {
      const { visible: a } = L("financeStatusPopup"),
        c = U(),
        { financeTitleMap: p, buttonTextMap: T, initData: g, index: h } = D(),
        { openDialog: v } = L("rechargeDetail"),
        A = m(() =>
          c.loadingState.loading
            ? "nested-loading"
            : c.loadingState.fetchError
            ? "retry"
            : null
        ),
        y = () =>
          e("div", { class: l["tips-title"] }, [p[c.financeStatusObj.type]]),
        S = m(() =>
          c.financeStatusObj.type === W.WITHDRAWAL_MESSAGE
            ? e(aa, { data: c.withdrawDetailsObj }, null)
            : c.financeStatusObj.type === W.PAY_MESSAGE
            ? e(Q, { data: c.payDetailsObj }, null)
            : ""
        ),
        f = async () => {
          var u;
          return (
            z("REFRESH_ORDER").emit({
              order:
                ((u = c.financeStatusObj) == null ? void 0 : u.orderNo) || "0",
            }),
            (c.financeStatusObj.orderNo = ""),
            !0
          );
        },
        C = async () => {
          c.financeStatusObj.state === M.PAY_CANCEL &&
            v("rechargeDetail", {
              openType: "overlay",
              orderNo: c.financeStatusObj.orderNo,
            }),
            c.financeStatusObj.state === M.WITHDRAWAL_CANCEL &&
              Z().push({
                name: J.WITHDRAWAL_RECORD_DETAILS,
                query: { orderNo: c.financeStatusObj.orderNo },
              });
        };
      return () =>
        e(
          Y,
          {
            show: a.value,
            "onUpdate:show": (u) => (a.value = u),
            class: l["fiance-main"],
            showConfirmButton: !0,
            confirmButtonText: T[c.financeStatusObj.state],
            title: y,
            zIndex: h.value,
            beforeClose: () => f(),
            onConfirm: () => C(),
          },
          {
            default: () => [
              e(
                q,
                { type: A.value, onRetry: () => g() },
                { default: () => [S.value] }
              ),
            ],
          }
        );
    },
  });
export { ia as default };
//# sourceMappingURL=FinanceStatusPopupIndex._OvgtOGX.js.map
