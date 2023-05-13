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

/**
 * Disable main items
 * @param {*} array
 */
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

/**
 * Disable items
 * @param {*} disabled ID list
 */
const disable = (disabled = []) => {
  [...disabled].forEach((item) => {
    if (document.querySelector("#" + item) !== null) {
      document.querySelector("#" + item).classList.add("disabled");
    }
  });
};

/**
 * Enable disabled items
 * @param {*} disabled
 */
const enable = (disabled = []) => {
  [...disabled].forEach((main) => {
    const item = document.querySelector("#" + main);
    if (item && item.classList.contains("disabled")) {
      item.classList.remove("disabled");
    }
  });
};

/**
 * Get all inputs from form
 * @returns DOMElementNodeList
 */
const getClearables = () => {
  const validateObject = document.querySelector("#createForm");

  return [
    ...validateObject.querySelectorAll("input"),
    ...validateObject.querySelectorAll("select"),
  ];
};

/**
 * Clear select options
 * @param {*} exclude
 * @returns
 */
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

const clearTaxValues = (
  zeros = false,
  accepts = ["taxable", "taxAmount", "subtotal"],
  deny = []
) => {
  [...accepts].map((item) => {
    const el = document.querySelector("#" + item);
    if (el && !deny.includes(item)) el.value = zeros ? 0.0 : null;
    return;
  });

  return true;
};

// validation
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

  // checking weather to ask for confrimation or not
  if (!confrim) {
    addToTwin(element, obj);
    if (deleteItem) deleteItem.remove();
    // main
    return true;
  }
  // confrim
  swal(swalConfiguration, () => {
    addToTwin(element, obj);
    if (deleteItem) deleteItem.remove();
  });

  // return main;
  return true;
};

/**
 * Get table rows from element
 * @returns
 */
const getTableRows = () => {
  return [...document.querySelector("#createTable").children];
};

/**
 * Find the Already existing twin
 * @param {*} obj
 * @param {*} array
 * @returns
 */
const isCreatingDataHasTwin = (
  obj,
  array = ["taxType", "taxPercentage", "productId"]
) => {
  const foundDuplicatedData = [...getTableRows()].filter((item) =>
    compare(item.dataset, obj, array)
  );
  return first(foundDuplicatedData) || false;
};

/**
 * Data being editted
 * @returns data | bool
 */
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
