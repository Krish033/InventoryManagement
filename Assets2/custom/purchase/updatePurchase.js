/**
 * Non reusable -> add items to elements
 * @param {*} twin
 * @param {*} obj
 * @returns
 */
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
  return true;
};

// append vlues while editing
const appendEditValues = async (e, row, tr) => {
  // clearing the error elements
  clearErrors();

  // if only has clear button
  if (!document.querySelector(".clearButton")) {
    generateCloseBtnOnEdit(tr);
  }

  updateEverything();

  tr.classList.add("editing");
  // add values to inboxes when editted
  Object.entries(row).map((item) => {
    const el = document.querySelector("#" + item[0]); // object entries returns an array with two tiems
    if (el) {
      // changing the elements values
      el.value = tr.getAttribute(`data-${item[0]}`);
    }
  });
  // getting and appending categories
  const { data, isError } = await requestCategories();
  const categoryId = document.querySelector("#categoryId");

  // caheck error and create error
  if (isError) return toastr.error("Something went wrong!", "Failed");
  createSelectOption(categoryId, data, "CID", "CName", tr.dataset.categoryid);

  const subCategoryId = document.querySelector("#subCategoryId");
  // getting sub categories purchase.subcategory
  const {
    data: sub,
    isError: subError,
    error,
  } = await requestSubCategories(tr.dataset.categoryid);
  if (subError) return toastr.error("Something went wrong!", "Failed");

  subCategoryId.classList.remove("disabled");
  createSelectOption(
    subCategoryId,
    sub,
    "SCID",
    "SCName",
    tr.dataset.subcategoryid
  );

  const productId = document.querySelector("#productId");
  const { data: product, isError: productError } = await requestProducts(
    tr.dataset.subcategoryid
  );
  if (productError) return toastr.error("Something went wrong!", "Failed");
  // getting sub categories purchase.subcategory
  productId.classList.remove("disabled");
  createSelectOption(productId, product, "pid", "name", tr.dataset.productid);
  const quantity = document.querySelector("#quantity");
  quantity.classList.remove("disabled");

  //getting and appending categories
  const { data: taxData, isError: isTaxError } = await requestTaxes();

  if (isTaxError) return toastr.error("Something went wrong!", "Failed");
  const taxSelect = document.querySelector("#taxPercentage");
  createSelectOption(
    taxSelect,
    taxData,
    "TaxPercentage",
    "TaxName",
    tr.dataset.taxpercentage
  );

  return true;
};
