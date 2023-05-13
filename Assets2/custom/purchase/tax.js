/**
 * -----------------------------------------------------------
 * ## Taxes@feelingPowerful
 * -----------------------------------------------------------
 * calculating tax was never easy before
 */
const Gst = new Tax();

/**
 * Imporatnt function -> update values
 * @returns
 */
const updateEverything = () => {
  // updatable values
  const quantity = document.querySelector("#quantity"),
    rate = document.querySelector("#price"),
    taxtype = document.querySelector("#taxType"),
    taxPercentage = document.querySelector("#taxPercentage"),
    taxable = document.querySelector("#taxable"),
    taxAmount = document.querySelector("#taxAmount"),
    subtotal = document.querySelector("#subtotal");

  // quantity value
  const quantityValue = parseFloat(quantity.value);
  // finding if quantity has to do something
  if (isNaN(quantityValue)) {
    clearTaxValues();
    return false;
  }

  if (quantityValue < 1) {
    clearTaxValues(true);
    return false;
  }

  // main rate value
  const rateValue = parseFloat(rate.value);
  // find if rate has something to offer
  if (isNaN(rateValue)) {
    clearTaxValues(true);
    return false;
  }
  // tax percentage
  const taxPercent = parseFloat(taxPercentage.value);
  // Finding if tax owe us something
  if (isNaN(taxPercent)) {
    clearTaxValues(true);
    return false;
  }

  const subTaxable = rateValue * quantityValue; // 2500
  // dont calculate tax if excluded
  if (taxtype.value == "include") {
    // get Includes tax
    const { taxAmount: mainTaxAmount, taxable: mainTaxableValue } =
      Gst.GstIncludes(subTaxable, taxPercent);

    taxable.value = Float.decimals(mainTaxableValue);
    taxAmount.value = Float.decimals(mainTaxAmount);

    subtotal.value = Float.decimals(mainTaxAmount + mainTaxableValue);
    return;
  }

  if (taxtype.value == "exclude") {
    // formula
    const { taxAmount: excludesTaxAmount, taxable: excludesTaxable } =
      Gst.GstExcludes(subTaxable, taxPercent);

    taxable.value = Float.decimals(subTaxable);
    taxAmount.value = Float.decimals(excludesTaxAmount);

    subtotal.value = Float.decimals(subTaxable + excludesTaxAmount);
    return;
  }
};
