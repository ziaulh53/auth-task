import { notification } from "ant-design-vue";

notification.config({
  placement: "bottomRight",
  bottom: "50px",
  duration: 2,
});
export const notify = (data, callBackFunction, callBackFunctionTwo) => {
  if (data?.success) {
    notification.success({
      message: data?.msg,
    });
    if (typeof callBackFunction === "function") {
      callBackFunction();
    }
    if (typeof callBackFunctionTwo === "function") {
      callBackFunctionTwo();
    }
  } else if (!data.success) {
    notification.warning({
      message: data?.msg,
    });
  } else {
    notification.error({
      message: "Somethinbg went wrong",
    });
  }
};
