export const getStatusColor = (status) => {
  switch (status) {
    case "Pending":
      return "yellow";
    case "Accepted":
      return "green";
    case "Shipped":
      return "blue";
    case "Delivered":
      return "black";
    case "Cancelled":
      return "red";

    default:
      return "green";
  }
};
