/**
 * create select options
 * @param {*} parent
 * @param {*} data
 * @param {*} key
 * @param {*} value
 * @param {*} selected
 * @returns
 */
const createSelect = (parent, data, key, value, selected) => {
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
