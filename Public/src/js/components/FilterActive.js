function FilterActiveMobile() {
  const BtnAtiveFilter = document.querySelector(".filter__active");
  const Filter = document.querySelector(".filtros");
  const Body = document.querySelector("body");
  if (!BtnAtiveFilter) return;
  if (!Filter) return;
  Body.addEventListener("click", (e) => {
    if (e.target !== BtnAtiveFilter && !Filter.contains(e.target)) {
      Filter.classList.remove("filtros--active");
    }
  });
  BtnAtiveFilter.addEventListener("click", () => {
    Filter.classList.toggle("filtros--active");
  });
}

export default FilterActiveMobile;
