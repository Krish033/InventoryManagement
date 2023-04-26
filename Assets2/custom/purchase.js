// craete option element
const createSelectOption = (parent, data, key, value, selected) => {
  // createing item
  data.map((item) => {
    const option = document.createElement("option");
    option.textContent = item[value];
    option.value = item[key];

    option.selected = item[key] == selected;

    parent.appendChild(option);
  }); // returning to avoid void function
  return true;
};

const totalAmount = (taxable, calculatedTaxAmount) => {
  const subtotal = document.querySelector("#subtotal");
  subtotal.classList.remove("disabled");
  subtotal.value = parseFloat(taxable) + parseFloat(calculatedTaxAmount);
};

// get category records
const requestCategories = () => {
  clearSelectInputs();
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

const calculateTaxAmount = (amount, taxPercentage) => {
  return (parseInt(amount) * parseInt(taxPercentage)) / 100;
};

const validateFunction = (nodeList) => {
  let validatedArray = {};
  let error = false;
  const subNodeList = [...nodeList];

  subNodeList.map((item) => {
    if (item.classList.contains("disabled") || empty(item.value)) {
      item.classList.add("bg-danger");
      error = true;
      return;
    }
    validatedArray = {
      ...validatedArray,
      [item.id]: item.value,
    };
  });

  if (error) {
    toastr.error("All fields are required");
    return false;
  }

  subNodeList.map((item) => {
    if (item instanceof HTMLSelectElement) {
      return;
    }
    // clearing the value
    return (item.value = "");
  });
  return validatedArray;
};

const genepriceGrandTotal = () => {
  const subtotal = requestTotalTax() + requestTotalInfo();
  document.querySelector("#grandTotal").textContent = subtotal;
  return subtotal;
};

const requestTotalTax = () => {
  const totalElement = [
    ...[...document.querySelectorAll("[data-tax-subtotal-element]")].map(
      (subtotal) => {
        return parseInt(subtotal.innerText);
      }
    ),
  ];

  const sum = totalElement.reduce((partialSum, a) => partialSum + a, 0);
  document.querySelector("#taxTotal").textContent = sum;
  return sum;
};

const requestTotalInfo = () => {
  const totalElement = [
    ...document.querySelectorAll("[data-subtotal-element]"),
  ].map((subtotal) => {
    return parseInt(subtotal.innerText);
  });

  const sum = totalElement.reduce((partialSum, a) => partialSum + a, 0);
  document.querySelector("#totalSum").textContent = sum;
  return sum;
};

const requestValidatedArray = () => {
  const validateObject = document.querySelector("#createForm");

  const validatables = [
    ...validateObject.querySelectorAll("input"),
    ...validateObject.querySelectorAll("select"),
  ];

  return validateFunction(validatables);
};

const createDOMElement = async (obj) => {
  const parentNode = document.querySelector("#createTable");
  const tr = document.createElement("tr");
  // adding lines to tr to easily get all
  tr.setAttribute("data-categoryId", obj.categoryId);
  tr.setAttribute("data-subCategoryId", obj.subCategoryId);
  tr.setAttribute("data-productId", obj.productId);

  tr.setAttribute("data-description", obj.description);
  tr.setAttribute("data-quantity", obj.quantity);
  tr.setAttribute("data-price", obj.price);

  tr.setAttribute("data-taxType", obj.taxType);
  tr.setAttribute("data-taxPercentage", obj.taxPercentage);
  tr.setAttribute("data-taxable", obj.taxable);

  tr.setAttribute("data-taxAmount", obj.taxAmount);
  tr.setAttribute("data-subtotal", obj.subtotal);
  parentNode.appendChild(tr);
  // category
  const category = document.createElement("td");
  category.textContent = obj?.categoryId;
  tr.appendChild(category);
  console.log(category);
  // category
  const subcategory = document.createElement("td");
  subcategory.textContent = obj.subCategoryId;
  tr.appendChild(subcategory);

  // getting product details
  const { data: singleProduct } = await requestSingleProducts(obj.productId);

  // category
  const product = document.createElement("td");
  product.textContent = singleProduct.name;
  tr.appendChild(product);
  // category
  const description = document.createElement("td");
  description.textContent = obj.description;
  tr.appendChild(description);
  // category
  const qty = document.createElement("td");
  qty.textContent = obj.quantity;
  tr.appendChild(qty);
  // category
  const price = document.createElement("td");
  price.textContent = obj.price;
  tr.appendChild(price);
  // category
  const taxType = document.createElement("td");
  taxType.textContent = obj.taxType;
  tr.appendChild(taxType);
  // category
  const taxPercentage = document.createElement("td");
  taxPercentage.textContent = obj.taxPercentage;
  tr.appendChild(taxPercentage);
  // category
  const taxable = document.createElement("td");
  taxable.textContent = obj.taxable;
  tr.appendChild(taxable);

  const taxAmount = document.createElement("td");
  taxAmount.textContent = obj.taxAmount;
  taxAmount.setAttribute("data-tax-subtotal-element", obj.taxAmount);
  tr.appendChild(taxAmount);

  const subtotal = document.createElement("td");
  subtotal.textContent = obj.subtotal;
  subtotal.setAttribute("data-subtotal-element", obj.subtotal);
  tr.appendChild(subtotal);
  // button
  const td = document.createElement("td");

  const btn = document.createElement("button");
  btn.className = "btn btn-sm btn-outline-danger";
  btn.innerHTML = '<i class="fa fa-close"></i>';

  const edit = document.createElement("button");
  edit.className = "btn btn-sm btn-outline-primary me-2";
  edit.innerHTML = '<i class="fa fa-pencil"></i>';

  td.appendChild(edit);
  td.appendChild(btn);

  tr.appendChild(td);

  btn.addEventListener("click", () => {
    tr.remove();
    genepriceGrandTotal();
  });

  edit.addEventListener("click", (e) => appendEditValues(e, obj, tr));

  genepriceGrandTotal();
  return true;
};

const requestItemsFromLocalStorage = () => {
  const data = JSON.parse(localStorage.getItem("main"));
  createDOMElement(data);
};

const clearSelectInputs = (exclude = []) => {
  const parentElement = document.querySelector("#createForm");
  const selectEl = parentElement.querySelectorAll("select");

  const excludeIds = ["taxType", ...exclude];

  [...selectEl].map((item) => {
    const defaultOption = document.createElement("option");

    if (excludeIds.includes(item.id)) {
      return;
    }

    item.innerHTML = "";

    defaultOption.textContent = "Select";
    defaultOption.value = "";
    defaultOption.disabled = "";

    item.appendChild(defaultOption);
  });

  return true;
};
