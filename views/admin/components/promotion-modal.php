<?php
$modalId   = $modalId   ?? 'promotionModal';
$actionUrl = $actionUrl ?? '/index.php?controller=adminPromotion&action=create';
?>

<div class="modal fade" id="<?php echo htmlspecialchars($modalId); ?>" tabindex="-1"
    aria-labelledby="<?php echo htmlspecialchars($modalId); ?>Label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form
                method="POST"
                action="<?php echo htmlspecialchars($actionUrl); ?>"
                class="needs-validation"
                novalidate
                id="<?php echo htmlspecialchars($modalId); ?>_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="<?php echo htmlspecialchars($modalId); ?>Label">
                        New Promotion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Hidden id: rỗng khi create, có giá trị khi edit -->
                    <input type="hidden" name="promotion_id" id="<?php echo $modalId; ?>_id" value="">

                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-12">
                            <label class="form-label">Promotion name</label>
                            <input
                                type="text"
                                name="promotion_name"
                                id="<?php echo $modalId; ?>_name"
                                class="form-control"
                                required
                                placeholder="e.g. Black Friday 30% off">
                            <div class="invalid-feedback">
                                Please enter a promotion name.
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="col-12 col-md-4">
                            <label class="form-label">Type</label>
                            <select name="promotion_type"
                                class="form-select"
                                id="<?php echo $modalId; ?>_type">
                                <option value="discount">Discount (%)</option>
                                <option value="fixed">Fixed price</option>
                            </select>
                        </div>

                        <!-- Discount % -->
                        <div class="col-12 col-md-4" data-role="discount-group">
                            <label class="form-label">Discount percentage</label>
                            <div class="input-group">
                                <input
                                    type="number"
                                    name="discount_percentage"
                                    id="<?php echo $modalId; ?>_discount"
                                    class="form-control"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    placeholder="e.g. 20">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <!-- Fixed price -->
                        <div class="col-12 col-md-4" data-role="fixed-group">
                            <label class="form-label">Fixed price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input
                                    type="number"
                                    name="fixed_price"
                                    id="<?php echo $modalId; ?>_fixed"
                                    class="form-control"
                                    step="0.01"
                                    min="0"
                                    placeholder="e.g. 49.99">
                            </div>
                        </div>

                        <!-- Start / End -->
                        <div class="col-12 col-md-6">
                            <label class="form-label">Start date</label>
                            <input
                                type="date"
                                name="start_date"
                                id="<?php echo $modalId; ?>_start"
                                class="form-control"
                                required>
                            <div class="invalid-feedback">
                                Please select a start date.
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">End date</label>
                            <input
                                type="date"
                                name="end_date"
                                id="<?php echo $modalId; ?>_end"
                                class="form-control"
                                required>
                            <div class="invalid-feedback">
                                Please select an end date (after start date).
                            </div>
                        </div>

                        <div class="col-12">
                            <p class="small text-muted mb-0">
                                • <strong>Discount</strong>: apply a percentage discount on the original price<br>
                                • <strong>Fixed price</strong>: set a fixed selling price for the product during the promotion period
                            </p>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <span class="text-muted me-auto small js-promo-dirty" style="display:none;">
                        Changes detected.
                    </span>
                    <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                        class="btn btn-primary js-promo-save"
                        disabled>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalId = '<?php echo $modalId; ?>';
        const modalEl = document.getElementById(modalId);
        if (!modalEl) return;

        const form = document.getElementById(modalId + '_form');
        const idInput = document.getElementById(modalId + '_id');
        const nameInput = document.getElementById(modalId + '_name');
        const typeSelect = document.getElementById(modalId + '_type');
        const discountInput = document.getElementById(modalId + '_discount');
        const fixedInput = document.getElementById(modalId + '_fixed');
        const startInput = document.getElementById(modalId + '_start');
        const endInput = document.getElementById(modalId + '_end');

        const discountGroup = modalEl.querySelector('[data-role="discount-group"]');
        const fixedGroup = modalEl.querySelector('[data-role="fixed-group"]');
        const saveBtn = modalEl.querySelector('.js-promo-save');
        const dirtyHint = modalEl.querySelector('.js-promo-dirty');
        const titleEl = document.getElementById(modalId + 'Label');

        if (!form || !typeSelect || !discountGroup || !fixedGroup || !saveBtn) return;

        // --- toggle discount / fixed ---
        function refreshTypeFields() {
            const val = typeSelect.value;
            if (val === 'discount') {
                discountGroup.classList.remove('d-none');
                fixedGroup.classList.add('d-none');
            } else {
                fixedGroup.classList.remove('d-none');
                discountGroup.classList.add('d-none');
            }
        }

        let initialState = null;

        function getState() {
            return {
                id: idInput.value || '',
                name: nameInput.value || '',
                type: typeSelect.value || '',
                discount: discountInput.value || '',
                fixed: fixedInput.value || '',
                start: startInput.value || '',
                end: endInput.value || ''
            };
        }

        function setState(state) {
            idInput.value = state.id ?? '';
            nameInput.value = state.name ?? '';
            typeSelect.value = state.type ?? 'discount';
            discountInput.value = state.discount ?? '';
            fixedInput.value = state.fixed ?? '';
            startInput.value = state.start ?? '';
            endInput.value = state.end ?? '';
            refreshTypeFields();
        }

        function updateDirty() {
            if (!initialState) {
                saveBtn.disabled = true;
                if (dirtyHint) dirtyHint.style.display = 'none';
                return;
            }
            const now = getState();
            const dirty = JSON.stringify(now) !== JSON.stringify(initialState);
            saveBtn.disabled = !dirty;
            if (dirtyHint) dirtyHint.style.display = dirty ? 'inline' : 'none';
        }

        typeSelect.addEventListener('change', refreshTypeFields);
        ['input', 'change'].forEach(evt => form.addEventListener(evt, updateDirty));

        // --- Delegation cho các nút có data-action ---
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;

            const action = btn.dataset.action;

            if (action === 'create') {
                form.action = '/index.php?controller=adminPromotion&action=create';

                setState({
                    id: '',
                    name: '',
                    type: 'discount',
                    discount: '',
                    fixed: '',
                    start: '',
                    end: ''
                });
                if (titleEl) titleEl.textContent = 'New Promotion';
                saveBtn.textContent = 'Create';
                initialState = getState();
                updateDirty();

            } else if (action === 'edit') {
                const d = btn.dataset;

                const pid = d.id || '';
                form.action = '/index.php?controller=adminPromotion&action=edit&promotion_id=' +
                    encodeURIComponent(pid);

                setState({
                    id: d.id || '',
                    name: d.name || '',
                    type: d.type || 'discount',
                    discount: d.discount || '',
                    fixed: d.fixed || '',
                    start: d.start || '',
                    end: d.end || ''
                });
                if (titleEl) titleEl.textContent = 'Edit Promotion #' + (d.id || '');
                saveBtn.textContent = 'Save changes';
                initialState = getState();
                updateDirty();
            }
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            form.classList.add('was-validated');
            saveBtn.disabled = true;

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(res) {
                    return res.json().catch(function() {
                        return {
                            success: false,
                            error: 'Invalid server response.'
                        };
                    });
                })
                .then(function(data) {
                    if (data.success) {
                        if (window.showToast) {
                            window.showToast(data.message || 'Promotion saved.', 'success');
                        }
                        const modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if (modalInstance) {
                            modalInstance.hide();
                        }

                        window.location.reload();
                    } else {
                        if (window.showToast) {
                            window.showToast(data.error || 'Something went wrong.', 'danger');
                        }
                        saveBtn.disabled = false;
                    }
                })
                .catch(function(err) {
                    console.error(err);
                    if (window.showToast) {
                        window.showToast('Network error. Please try again.', 'danger');
                    }
                    saveBtn.disabled = false;
                });
        });

        modalEl.addEventListener('hidden.bs.modal', function() {
            form.classList.remove('was-validated');
            initialState = null;
            saveBtn.disabled = true;
            if (dirtyHint) dirtyHint.style.display = 'none';
        });

        refreshTypeFields();
    });
</script>