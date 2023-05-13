// get category records
const requestCategories = () => {
  // getting the tax
  const ajaxConfiguration = {
    method: "GET",
    type: "GET",
    url: "/transactions/api/purchase/requestCategory",
  }; // main data
  const data = request.http(ajaxConfiguration);
  return data;
};

// get category records
const requestSubCategories = (cid) => {
  // getting the tax
  const ajaxConfiguration = {
    method: "GET",
    url: "/transactions/api/purchase/request-subCategory/" + cid,
  }; // main data

  const data = request.http(ajaxConfiguration);
  return data;
};

// get category products
const requestProducts = (scId) => {
  // getting the tax
  const ajaxConfiguration = {
    method: "GET",
    type: "GET",
    url: "/transactions/api/purchase/request-products/" + scId,
  }; // main data

  const data = request.http(ajaxConfiguration);
  return data;
};

// get category products
const requestSingleProducts = (pid) => {
  // getting the tax
  const ajaxConfiguration = {
    method: "GET",
    type: "GET",
    url: "/transactions/api/purchase/request-single-products/" + pid,
  }; // main data

  const data = request.http(ajaxConfiguration);
  return data;
};

// get category products
const requestTaxes = () => {
  // getting the tax
  const ajaxConfiguration = {
    method: "GET",
    type: "GET",
    url: "/transactions/api/purchase/requestTax",
  }; // main data

  const data = request.http(ajaxConfiguration);
  return data;
};

// get category products
const requestSuppliers = () => {
  // getting the tax
  const ajaxConfiguration = {
    method: "GET",
    type: "GET",
    url: "/transactions/api/purchase/request-suppliers",
  }; // main data

  const data = request.http(ajaxConfiguration);
  return data;
};
