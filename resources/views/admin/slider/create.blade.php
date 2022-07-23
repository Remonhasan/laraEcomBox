@extends('admin-layouts.master')

@section('styles')
    <style>
        .error-msg {
            color: red;
        }

        input.msg-box {
            border: 1px solid red;
        }

        .msg-hidden {
            display: none;
        }

    </style>
@endsection

@section('admin_content')
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">{{ __('Sliders') }}</h1>
        <ol class="breadcrumb shadow mb-4">
            <i class="fa-solid fa-border-all mt-1 mr-2"></i>
            <li class="breadcrumb-item">{{ __('Slider') }}</li>
            <li class="breadcrumb-item active"><a href="{{ route('slider.list') }}">{{ __('List') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Add') }}</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <i class="fas fa-radio mt-2 mr-1"></i>
                        {{ __('Create New Slider') }}
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <a href="{{ route('category.list') }}" type="button" class="btn btn-primary"><i class="fas fa-list me-1"></i>{{ __('List') }}</a>
                    </div>
                </div>
            </div>
            <div class="card-body shadow">
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" class="mb-2">{{ __('Title ( In English )') }}
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" name="title" autocomplete="off" class="form-control" placeholder="{{ __('Name ( In English )') }}">
                                <small class="error-msg msg-hidden ml-1">{{ __('Name (In English) is required.') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sub-title" class="mb-2">{{ __('Sub Title ( In English )') }}
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" name="sub_title" autocomplete="off" class="form-control" placeholder="{{ __('Name ( In Bangla )') }}">
                                <small class="error-msg msg-hidden ml-1">{{ __('Name (In Bangla) is required.') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="is-active" class="mb-2">{{ __('Image') }}</label>
                                <select name="is_active" class="form-control" id="isActive">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="is-active" class="mb-2">{{ __('Status') }}</label>
                                <select name="is_active" class="form-control" id="isActive">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 float-right">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')

<script type="text/javascript">
        $(document).ready(function() {
            const form = document.querySelector('form');
            // Get Input 
            const nameInputEn = document.querySelector('input[name="name_en"]')
            const nameInputBn = document.querySelector('input[name="name_bn"]')
            // Checked Validation Status
            let isFormValid = false;
            // Check Validation
            const validateInputs = () => {
                // Remove Invalid
                nameInputEn.classList.remove("invalid");
                nameInputBn.classList.remove("invalid");
                // Remove Error Message
                nameInputEn.nextElementSibling.classList.add("msg-hidden");
                nameInputBn.nextElementSibling.classList.add("msg-hidden");
                // Check when input is null
                if (!nameInputEn.value) {
                    nameInputEn.classList.add("invalid");
                    nameInputEn.nextElementSibling.classList.remove("msg-hidden");
                    isFormValid = false;
                } else {
                    isFormValid = true;
                }
                if (!nameInputBn.value) {
                    nameInputBn.classList.add("invalid");
                    nameInputBn.nextElementSibling.classList.remove("msg-hidden");
                    isFormValid = false;
                } else {
                    isFormValid = true;
                }
            }
            // Check Submit Event
            form.addEventListener("submit", (e) => {
                e.preventDefault();
                validateInputs();
                console.log(isFormValid);
                if (isFormValid) {
                    form.submit();
                }
            });
            // Add Invalid Color and Necessaries
            nameInputEn.addEventListener("input", () => {
                validateInputs();
            });
            nameInputBn.addEventListener("input", () => {
                validateInputs();
            });
        });
    </script>

@endsection