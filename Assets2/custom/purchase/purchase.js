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

const getCategoryItems = async () => {
  const category = document.querySelector("#categoryId");
  const { data } = await requestCategories();
  createSelectOption(category, data, "CID", "CName");
  return data;
};

// ! loading categories
$(document).ready(async () => {
  //getting and appending categories
  const { data, isError } = await requestCategories();

  updateEverything();

  const categoryId = document.querySelector("#categoryId");
  $("select:not(#taxType, #taxPercentage)").select2();
  // caheck error and create error
  if (isError) return toastr.error("Something went wrong!", "Failed");
  createSelectOption(categoryId, data, "CID", "CName");
});

// fetch subcategories when category value changes
$("#categoryId").change(async function (e) {
  updateEverything();
  clearSelectInputs(["categoryId"]);
  // clear subcategories
  const subCategoryId = document.querySelector("#subCategoryId");
  $(".notice").html("");
  enable(["taxType", "taxPercentage"]);

  const { data, isError } = await requestSubCategories(e.target.value);

  if (isError) return toastr.error("Something went wrong!", "Failed");

  subCategoryId.classList.remove("disabled");
  createSelectOption(subCategoryId, data, "SCID", "SCName");
});

// fetch products when subcategory change
$("#subCategoryId").change(async function (e) {
  updateEverything();
  clearSelectInputs(["categoryId", "subCategoryId"]);
  $(".notice").html("");
  enable(["taxType", "taxPercentage"]);
  // clear subcategories
  const productId = document.querySelector("#productId");
  const { data, isError } = await requestProducts(e.target.value);

  if (isError) return toastr.error("Something went wrong!", "Failed");
  // getting sub categories purchase.subcategory
  productId.classList.remove("disabled");

  createSelectOption(productId, data, "pid", "name");
});

// clear subcategories
$("#productId").change(async function (e) {
  clearSelectInputs(["categoryId", "subCategoryId", "productId"]);
  updateEverything();

  $(".notice").html("");
  enable(["taxType", "taxPercentage"]);
  updateEverything();

  // check for duplicates
  const productId = document.querySelector("#productId");

  const duplicate = isCreatingDataHasTwin(
    {
      productId: productId.value,
    },
    ["productId"]
  ); // dom element

  if (duplicate) {
    toastr.info("Appending to it", "Item already availabe");
  }

  // getting a single product -> only if duplicate not found
  const { data, isError } = await requestSingleProducts(e.target.value);
  if (isError) return toastr.error("Something went wrong!", "Failed");

  //getting and appending tax values
  const { data: taxData, isError: isTaxError } = await requestTaxes();

  if (isTaxError) return toastr.error("Something went wrong!", "Failed");

  const taxSelect = document.querySelector("#taxPercentage");
  createSelectOption(
    taxSelect,
    taxData,
    "TaxPercentage",
    "TaxName",
    data?.taxId
  );

  // disable quantity
  const quantity = document.querySelector("#quantity");
  quantity.classList.remove("disabled");

  if (duplicate) {
    // got main values
    const dataset = duplicate.dataset;

    const notice = document.querySelector(".notice");

    if (!confrimUpdate) {
      $(".notice").html(
        '<i class="fa-solid fa-circle-exclamation"></i> This product will be automatically added to the already existing product'
      );
    }

    // generate object without categoryId subCategoryId, productId
    const objectData = Arr.object(dataset)
      .except([
        "categoryId",
        "subCategoryId",
        "productId",
        "quantity",
        "taxAmount",
        "taxable",
        "subtotal",
      ])
      .get();

    // got the items append it on screen
    const parentNode = document.querySelector("#createForm");

    const append = createAppend(parentNode);

    append.includesSelect().ids(objectData).create();

    disable(["taxType", "taxPercentage"]);
    updateEverything();
    return true;
  }
  updateEverything();
  document.querySelector("#price").value = data?.purchaseRate;
});

// quantity changes
$(document).on("input", "#quantity", async function (e) {
  updateEverything();
  return true;
});

// taxtype changes
$("#taxType").change(async function (e) {
  updateEverything();
});

// percentage changes
$("#taxPercentage").change(async function (e) {
  updateEverything();
});
