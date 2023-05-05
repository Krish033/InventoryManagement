// craete option element
const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

const currency = new Intl.NumberFormat("en-IN", {
  style: "currency",
  currency: "INR",
  useGrouping: false,
});

const createSelectOption = (parent, data, key, value, selected) => {
  // createing item
  if (!(data instanceof Array)) {
    console.log("string");
    return toastr.error('Something went wrong!', 'Error');
  }

  data?.map((item) => {
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

const validateFunction = (nodeList) => {
  let validatedArray = {};

  let error = false;
  const subNodeList = [...nodeList];

  subNodeList.map((item) => {
    if (empty(item.value)) {
      if (item.id != "description") {
        item.classList.add("validation-error");
        error = true;
        return;
      }
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

  return validatedArray;
};

const clearInputs = (subNodeList = getClearables()) => {
  subNodeList.map(async (item) => {
    if (item instanceof HTMLSelectElement) {
      return;
    }
    // clearing the value
    item.value = "";
    clearSelectInputs();

    disableInputs();
    return true;
  });
};

const getCategoryItems = async () => {
  const category = document.querySelector("#categoryId");
  const { data } = await requestCategories();
  createSelectOption(category, data, "CID", "CName");
  return data;
};

const genepriceGrandTotal = () => {
  const subtotal = requestTotalTax() + requestTotalInfo();
  document.querySelector("#grandTotal").textContent = currency.format(subtotal);
  return subtotal;
};

const requestTotalTax = () => {
  const totalElement = [
    ...[...document.querySelectorAll("[data-tax-subtotal-element]")].map(
      (subtotal) => {
        const parent = subtotal.parentElement;
        return parseFloat(parent.dataset.taxamount);
      }
    ),
  ];

  const sum = totalElement.reduce((partialSum, a) => partialSum + a, 0);
  document.querySelector("#taxTotal").textContent = currency.format(sum);
  return sum;
};

const disableInputs = () => {
  const disabled = ["quantity", "price", "tax", "subCategoryId", "productId"];

  disabled.forEach((item) => {
    if (document.querySelector("#" + item) !== null) {
      document.querySelector("#" + item).classList.add("disabled");
    }
  });
};

const requestTotalInfo = () => {
  const totalElement = [
    ...document.querySelectorAll("[data-subtotal-element]"),
  ].map((subtotal) => {
    const parent = subtotal.parentElement;
    return parseFloat(parent.dataset.taxable);
  });

  const sum = totalElement.reduce((partialSum, a) => partialSum + a, 0);
  document.querySelector("#totalSum").textContent = currency.format(sum);
  return sum;
};

const requestValidatedArray = () => {
  return validateFunction(getClearables());
};

const getClearables = () => {
  const validateObject = document.querySelector("#createForm");

  return [
    ...validateObject.querySelectorAll("input"),
    ...validateObject.querySelectorAll("select"),
  ];
};

const hasTwin = (obj) => {
  const parentNode = document.querySelector("#createTable");
  const twin = parentNode.children;

  let found = false;
  [...twin].map((item) => {
    if (
      item.dataset.taxtype === obj.taxType &&
      item.dataset.taxpercentage == obj.taxPercentage
    ) {
      found = true;
    }
  });

  return found;
};

const getItem = (obj) => {
  const parentNode = document.querySelector("#createTable");
  const twin = parentNode.children;

  const main = [];

  [...twin].map((item) => {
    if (
      item.dataset.taxtype === obj.taxType &&
      item.dataset.taxpercentage == obj.taxPercentage
    ) {
      main.push(item);
    }
  });

  return main[0];
};

const addToTwin = (obj) => {
  const parentNode = document.querySelector("#createTable");

  const swalConfiguration = {
    title: "Cannot create new Product?",
    text: "THe product you have created has a twin!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-outline-success",
    confirmButtonText: "Update product",
    closeOnConfirm: true,
  };

  swal(swalConfiguration, () => {
    const twin = getItem(obj);

    // quantity
    twin.querySelector("#item-quantity").innerText =
      parseFloat(twin.dataset.quantity) + parseFloat(obj.quantity);
    twin.setAttribute(
      "data-quantity",
      parseFloat(twin.dataset.quantity) + parseFloat(obj.quantity)
    );

    twin.querySelector("#item-taxable").innerText = currency.format(
      parseFloat(twin.dataset.taxable) + parseFloat(obj.taxable)
    );
    twin.setAttribute(
      "data-taxable",
      parseFloat(twin.dataset.taxable) + parseFloat(obj.taxable)
    );

    twin.querySelector("#item-taxAmount").innerText = currency.format(
      parseFloat(twin.dataset.taxamount) + parseFloat(obj.taxAmount)
    );
    twin.setAttribute(
      "data-taxAmount",
      parseFloat(twin.dataset.taxamount) + parseFloat(obj.taxAmount)
    );

    twin.querySelector("#item-subtotal").innerText = currency.format(
      parseFloat(twin.dataset.subtotal) + parseFloat(obj.subtotal)
    );
    twin.setAttribute(
      "data-subtotal",
      parseFloat(twin.dataset.subtotal) + parseFloat(obj.subtotal)
    );

    twin.querySelector("#item-description").innerText = obj?.description;
    clearInputs();
    getCategoryItems();
    genepriceGrandTotal();
  });
  return true;
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
  description.setAttribute("id", "item-description");
  tr.appendChild(description);
  // category
  const qty = document.createElement("td");
  qty.textContent = obj.quantity;
  qty.setAttribute("id", "item-quantity");
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
  // qty.setAttribute("id", "item-quantity");
  taxable.id = "item-taxable";
  taxable.textContent = currency.format(obj.taxable);
  tr.appendChild(taxable);

  const taxAmount = document.createElement("td");
  taxAmount.textContent = currency.format(obj.taxAmount);
  taxAmount.id = "item-taxAmount";
  taxAmount.setAttribute("data-tax-subtotal-element", obj.taxAmount);
  tr.appendChild(taxAmount);

  const subtotal = document.createElement("td");
  subtotal.id = "item-subtotal";
  subtotal.textContent = currency.format(obj.subtotal);
  taxable.setAttribute("data-subtotal-element", obj.taxable);
  tr.appendChild(subtotal);
  // button
  const td = document.createElement("td");
  td.classList.add("btn-td");
  const btn = document.createElement("button");
  btn.className = "btn btn-sm btn-outline-danger";
  btn.innerHTML = '<i class="fa fa-trash"></i>';

  const edit = document.createElement("button");
  edit.className = "btn btn-sm btn-outline-primary me-2";
  edit.innerHTML = '<i class="fa fa-pencil"></i>';

  td.appendChild(edit);
  td.appendChild(btn);
  tr.appendChild(td);

  btn.addEventListener("click", () => {
    const swalConfiguration = {
      title: "Are you sure?",
      text: "Create this purchase!",
      type: "info",
      showCancelButton: true,
      confirmButtonClass: "btn-outline-success",
      confirmButtonText: "Confrim",
      closeOnConfirm: true,
    };

    swal(swalConfiguration, () => {
      tr.remove();
      genepriceGrandTotal();
    });
  });

  edit.addEventListener("click", (e) => appendEditValues(e, obj, tr));

  genepriceGrandTotal();
  clearInputs();
  getCategoryItems();

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

const calculateTaxAmount = (amount, taxPercentage) => {
  return (parseFloat(amount) * parseFloat(taxPercentage)) / 100;
};

const updateEverything = () => {
  // updatable values
  const quantity = document.querySelector("#quantity"),
    rate = document.querySelector("#price"),
    taxtype = document.querySelector("#taxType"),
    taxPercentage = document.querySelector("#taxPercentage"),
    taxable = document.querySelector("#taxable"),
    taxAmount = document.querySelector("#taxAmount"),
    subtotal = document.querySelector("#subtotal");
  // quantity change

  const quantityValue = parseFloat(quantity.value);
  if (isNaN(quantityValue)) return false;

  const rateValue = parseFloat(rate.value);
  const taxPercent = parseFloat(taxPercentage.value);
  // get values
  taxable.value = quantityValue * rateValue;
  const taxableValue = parseFloat(taxable.value);
  // calcualte tax amount
  taxAmount.value = calculateTaxAmount(
    taxableValue,
    isNaN(taxPercent) ? 0.0 : taxPercent
  );
  // dont calculate tax if excluded
  if (taxtype.value == "include") {
    if (!isNaN(taxPercent)) {
      //formula
      // ! GSTCalculatedTotalAmount * (100 / (100 + taxPercent))
      const GSTCalculatedTotalAmount = calculateTaxAmount(
        taxable.value,
        taxPercent
      ); // GSTCalculatedTotalAmount

      const main = GSTCalculatedTotalAmount * (100 / (100 + taxPercent));
      const subTaxable = rateValue * quantityValue; // 2500
      taxable.value = subTaxable - main;
      taxAmount.value = main;
    }
  }

  if (taxtype.value == "exclude") {
    if (!isNaN(taxPercent)) {
      // formula
      // ! taxableValue + taxableValue * (taxPercent / 100) - taxableValue
      taxAmount.value =
        taxableValue + taxableValue * (taxPercent / 100) - taxableValue;
    }
  }
  // calculating sub total
  subtotal.value = parseFloat(taxable.value) + parseFloat(taxAmount.value);
};
