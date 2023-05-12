/**
 * Fetch categories
 * @returns object
 */
const categories = () => {
  // getting the tax
  const ajaxConfiguration = {
    method: "GET",
    type: "GET",
    url: "/transactions/api/purchase/requestCategory",
  }; // main data
  const data = request.http(ajaxConfiguration);
  return data;
};

