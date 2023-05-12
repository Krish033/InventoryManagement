// craete option element
const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

/**
 * update already existing items
 * @confrimUpdate
 * @default true
 */
const confrimUpdate = false;

const currency = new Intl.NumberFormat("en-IN", {
  style: "currency",
  currency: "INR",
  useGrouping: false,
});

const createSelectOption = (parent, data, key, value, selected) => {
  // createing item
  if (!(data instanceof Array)) {
    return toastr.error("Something went wrong!", "Error");
  }

  const defaultOpt = defaultOption();
  parent.innerHTML = "";
  parent.appendChild(defaultOpt);

  data?.map((item) => {
    const option = document.createElement("option");
    option.textContent = item[value];
    option.value = item[key];

    option.selected = item[key] == selected;

    parent.appendChild(option);
  }); // returning to avoid void function
  return true;
};

const defaultOption = () => {
  const defaultOption = document.createElement("option");

  defaultOption.textContent = "Select";
  defaultOption.value = "";
  defaultOption.disabled = "";

  return defaultOption;
};

const totalAmount = (taxable, calculatedTaxAmount) => {
  const subtotal = document.querySelector("#subtotal");
  subtotal.classList.remove("disabled");
  subtotal.value = parseFloat(taxable) + parseFloat(calculatedTaxAmount);
};

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

const disableInputs = (array = []) => {
  const disabled = [
    "quantity",
    "price",
    "tax",
    "subCategoryId",
    "productId",
    ...array,
  ];

  disabled.forEach((item) => {
    if (document.querySelector("#" + item) !== null) {
      document.querySelector("#" + item).classList.add("disabled");
    }
  });
};

const disable = (disabled = []) => {
  [...disabled].forEach((item) => {
    if (document.querySelector("#" + item) !== null) {
      document.querySelector("#" + item).classList.add("disabled");
    }
  });
};

const enable = (disabled = []) => {
  [...disabled].forEach((main) => {
    const item = document.querySelector("#" + main);
    if (item && item.classList.contains("disabled")) {
      item.classList.remove("disabled");
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

  // object has product id
  const mainTwin = parentNode.querySelector(
    `[data-productId='${obj.productId}']`
  );

  const beingEdited = document.querySelector(".editing");

  let found = false;
  [...twin].map((item) => {
    if (
      mainTwin != null &&
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

const addToTwin = (twin, obj) => {
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

  twin.querySelector("#item-description").textContent = obj?.description;
  twin.setAttribute("data-description", obj?.description);

  clearInputs();

  getCategoryItems();
  genepriceGrandTotal();
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
  category.textContent = obj?.category;
  tr.appendChild(category);
  // category
  const subcategory = document.createElement("td");
  subcategory.textContent = obj.subCategory;
  tr.appendChild(subcategory);

  // category
  const product = document.createElement("td");
  product.textContent = obj.product;
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
  qty.setAttribute("align", "right");
  tr.appendChild(qty);
  // category
  const price = document.createElement("td");
  price.textContent = obj.price;
  price.setAttribute("align", "right");
  tr.appendChild(price);
  // category
  const taxType = document.createElement("td");
  taxType.textContent = obj.taxType;
  tr.appendChild(taxType);
  // category
  const taxPercentage = document.createElement("td");
  taxPercentage.textContent = obj.taxPercentage;
  taxPercentage.setAttribute("align", "right");
  tr.appendChild(taxPercentage);
  // category
  const taxable = document.createElement("td");
  // qty.setAttribute("id", "item-quantity");
  taxable.id = "item-taxable";
  taxable.setAttribute("align", "right");

  taxable.textContent = currency.format(obj.taxable);
  tr.appendChild(taxable);

  const taxAmount = document.createElement("td");
  taxAmount.textContent = currency.format(obj.taxAmount);
  taxAmount.id = "item-taxAmount";
  taxAmount.setAttribute("align", "right");

  taxAmount.setAttribute("data-tax-subtotal-element", obj.taxAmount);
  tr.appendChild(taxAmount);

  const subtotal = document.createElement("td");
  subtotal.id = "item-subtotal";
  subtotal.textContent = currency.format(obj.subtotal);
  taxable.setAttribute("data-subtotal-element", obj.taxable);
  subtotal.setAttribute("align", "right");

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
      text: "remove this product from the list!",
      type: "danger",
      showCancelButton: true,
      confirmButtonClass: "btn-outline-danger",
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
      // @ GSTCalculatedTotalAmount * (100 / (100 + taxPercent))
      const GSTCalculatedTotalAmount = calculateTaxAmount(
        taxable.value,
        taxPercent
      ); // GSTCalculatedTotalAmount

      const main = GSTCalculatedTotalAmount * (100 / (100 + taxPercent));
      const subTaxable = rateValue * quantityValue; // 2500
      taxable.value = Float.decimals(subTaxable - main);
      taxAmount.value = Float.decimals(main);
    }
  }

  if (taxtype.value == "exclude") {
    if (!isNaN(taxPercent)) {
      // formula
      // @ taxableValue + taxableValue * (taxPercent / 100) - taxableValue
      taxAmount.value = Float.decimals(
        taxableValue + taxableValue * (taxPercent / 100) - taxableValue
      );
    }
  }
  // calculating sub total
  subtotal.value = Float.decimals(
    parseFloat(taxable.value) + parseFloat(taxAmount.value)
  );
};

// upgraded

//! 1. validation
const validateInputs = (obj, deny = ["description"]) => {
  var mainError = true;
  Object.entries(obj).map((item) => {
    if (empty(item[1]) && !deny.includes(item[0])) {
      const errorEl = document.querySelector(`#${item[0]}`);
      errorEl?.classList.add("validation-error");
      mainError = false;
      return true;
    }
  }); // converted error
  return mainError;
};

/**
 * clear generated Error messages
 */
const clearErrors = () => {
  const errorEl = [...document.querySelectorAll(".validation-error")];
  errorEl.forEach((item) => item?.classList.remove("validation-error"));
};

/**
 * This function speaks for itself
 */
const generateCloseBtnOnEdit = (tr) => {
  const closeBtn = document.createElement("button");
  // class name
  closeBtn.className = "btn btn-sm btn-outline-danger ms-2 col-1 clearButton";
  // inner i tag
  closeBtn.innerHTML = '<i class="fa fa-close"></i>';
  // paren
  const form = document.querySelector(".createFormRow");
  form.appendChild(closeBtn);

  // also an event to clear inputs
  closeBtn.addEventListener("click", (event) => {
    // ! make the menu as not being edited
    tr.classList.remove("editing");
    closeBtn.remove(); // remove the button itself
    // clear the input fields
    clearInputs();
    getCategoryItems(); // refetch categories
  });
};

/**
 * Compares two elements
 * @param {*} obj1 item to be compared with
 * @param {*} obj2 item to comapre
 * @param {*} compareArray compare only with
 * @returns ``` bool ``` if the match found
 */
const compare = (obj1, obj2, compareArray, complete = false) => {
  let tempArray = [];
  // finding the similar items
  Object.entries(obj2).map((item) => {
    if (obj1.hasOwnProperty(item[0].toLocaleLowerCase())) {
      // check if the value is same
      obj1[item[0].toLocaleLowerCase()] === item[1]
        ? tempArray.push(item[0])
        : "";
    }
  });
  // if has to compare all the items
  const loopingArray = Boolean(complete)
    ? [...Object.keys(obj1)]
    : [...compareArray];

  const check = loopingArray.map((item) => {
    return tempArray.includes(item) ? true : false;
  });

  return !check.includes(false);
};

/**
 * If the created element already lives append to it
 * @param {*} obj
 * @returns
 */
const appendItemToAlreadyAvailabeItem = (
  element,
  obj,
  deleteItem = null,
  confrim = true
) => {
  // confrim to update
  const swalConfiguration = {
    title: "Already Available",
    text: "The product entered is already available, add to the product?",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-outline-success",
    confirmButtonText: "Update product",
    closeOnConfirm: true,
  };

  if (!confrim) {
    addToTwin(element, obj);
    if (deleteItem) deleteItem.remove();

    return true;
  }

  swal(swalConfiguration, () => {
    addToTwin(element, obj);
    if (deleteItem) deleteItem.remove();
  });

  // return main;
  return true;
};

const getTableRows = () => {
  return [...document.querySelector("#createTable").children];
};

// twin of creating data
const isCreatingDataHasTwin = (
  obj,
  array = ["taxType", "taxPercentage", "productId"]
) => {
  const foundDuplicatedData = [...getTableRows()].filter((item) =>
    compare(item.dataset, obj, array)
  );
  return first(foundDuplicatedData) || false;
};

// being editted data
const beingEdittedData = () => {
  const data = document.querySelector(".editing");
  return data || false;
};

// editing data twin
const isEditingDataHasTwin = (obj) => {
  const foundDuplicatedData = [...getTableRows()].filter(
    (item) =>
      compare(item.dataset, obj, ["taxType", "taxPercentage", "productId"]) &&
      !item.classList.contains("editing")
  );
  return first(foundDuplicatedData) || false;
};

// product change
const productHasChanged = (obj) => {};

const append = (data, deny = []) => {
  // add values to inboxes when editted
  Object.entries(data).map((item) => {
    const el = document.querySelector("#" + item[0]); // object entries returns an array with two tiems
    if (el && !deny.includes(item[0])) {
      // changing the elements values
      el.value = tr.getAttribute(`data-${item[0]}`);
    }
  });
};

const comfrimUpdate = () => {
  return document.querySelector(".confrimUpdate").checked;
};
