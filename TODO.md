# TODO

## SIZE + COLOR VARIANTS “Generate by Color”
- [ ] Inspect current variant UI (done)
- [ ] Implement matrix generator UI in `resources/views/admin/products/form.blade.php`
  - [ ] Add controls to choose colors + sizes per color (and/or per-color section)
  - [ ] Add “Generate Variants” button to auto-create variant rows (color x size)
  - [ ] Keep existing manual “Add Row” workflow
- [ ] Ensure generated rows use correct input names: `variants[i][size]`, `variants[i][color]`, etc.
- [ ] Smoke test: Black(5 sizes) + White(6 sizes) => 11 rows saved

