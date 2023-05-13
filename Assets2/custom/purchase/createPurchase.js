/**
 * Non reusable Creating element for DOM -> tr
 * @param {*} obj
 * @returns
 */
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
    });
  });

  edit.addEventListener("click", (e) => appendEditValues(e, obj, tr));

  clearInputs();
  getCategoryItems();

  return true;
};

// submit records
$(document).on("click", "#createPurchaseItemForm", async (e) => {
  // todo: get product
  e.preventDefault();
  enable(["taxType", "taxPercentage"]);
  $(".notice").html("");
  updateEverything();

  // getting text values
  const category = document.querySelector("#categoryId");
  const subcategory = document.querySelector("#subCategoryId");
  const product = document.querySelector("#productId");

  const categoryName = category.options[category.selectedIndex].text;
  const subCategoryName = subcategory.options[subcategory.selectedIndex].text;
  const productName = product.options[product.selectedIndex].text;

  // data, editted and created data uses the same mainValidated
  const mainValidated = {
    category: categoryName,
    categoryId: category.value,
    subCategory: subCategoryName,

    subCategoryId: subcategory.value,
    product: productName,
    productId: product.value,

    quantity: $("#quantity").val(),
    price: $("#price").val(),
    taxType: $("#taxType").val(),
    description: $("#description").val(),
    taxPercentage: $("#taxPercentage").val(),
    taxable: $("#taxable").val(),
    taxAmount: $("#taxAmount").val(),

    subtotal: $("#subtotal").val(),
  };

  // validation
  if (!validateInputs(mainValidated)) {
    toastr.error("Please fill in all fields", "Validation Error");
    updateEverything();
    return false;
  }

  // validation
  if (parseFloat(mainValidated.quantity) < 1) {
    toastr.error("Quantity cannot be empty", "Validation Error");
    updateEverything();
    return false;
  }

  // clearing the error elements
  clearErrors();
  updateEverything();

  if (document.querySelector(".clearButton")) {
    document.querySelector(".clearButton").remove();
  }

  // todo: check if product is being edited
  // get editted data
  const originalDataBeingEditted = beingEdittedData();

  // todo: if being edited, check if edited data has twin
  const editingDuplicateData = beingEdittedData()
    ? isEditingDataHasTwin(mainValidated)
    : false;
  // find twin, which is not original item
  if (Boolean(editingDuplicateData)) {
    // is being editted and found a duplicate

    updateEverything();
    // add data to the duplicated
    const beingEdittedDataForDeletion = beingEdittedData();

    appendItemToAlreadyAvailabeItem(
      editingDuplicateData,
      mainValidated,
      beingEdittedDataForDeletion
    );

    if (document.querySelector(".editing")) {
      document.querySelector(".editing").classList.remove(".editing");
    }
    return true;
  }

  // todo: if edited data doesnot have a twin
  if (originalDataBeingEditted) {
    updateEverything();
    // remove original data
    originalDataBeingEditted.remove();
    // insert new data
    createDOMElement({
      ...mainValidated,
    });

    enable(["taxType", "taxPercentage"]);

    if (document.querySelector(".editing")) {
      document.querySelector(".editing").classList.remove(".editing");
    }

    return true;
  }

  // todo: if not being edited, check if the product has twin
  const notBeingEditedDuplicate = isCreatingDataHasTwin(mainValidated);

  // alreadyExists -> not being editted
  if (notBeingEditedDuplicate) {
    // add data to the twin
    appendItemToAlreadyAvailabeItem(
      notBeingEditedDuplicate,
      mainValidated,
      null,
      confrimUpdate
    );

    enable(["taxType", "taxPercentage"]);

    if (document.querySelector(".editing")) {
      document.querySelector(".editing").classList.remove(".editing");
    }
    return true;
  }
  // add product to twin
  updateEverything();
  createDOMElement({
    ...mainValidated,
  });

  if (document.querySelector(".editing")) {
    document.querySelector(".editing").classList.remove(".editing");
  }

  enable(["taxType", "taxPercentage"]);
  return true;
  // crate product
});
