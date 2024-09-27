<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-0">Add Dealer</h1>
                    <hr />
                    @if (session()->has('error'))
                    <div>
                        {{session('error')}}
                    </div>
                    @endif
                    <p><a href="{{ route('admin/dealers') }}" class="btn btn-primary">Go Back</a></p>
 
                    <form action="{{ route('admin/dealers/save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="dealer_id" class="form-control" placeholder="Dealer ID">
                                @error('dealer_id')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="name" class="form-control" placeholder="Name">
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="email" class="form-control" placeholder="Email">
                                @error('email')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="appuid" class="form-control" placeholder="App Uid" value={{ generate_app_uid(6) }} readonly>
                                @error('appuid')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="current_url" class="form-control" placeholder="Current URL">
                                <!-- @error('current_url')
                                <span class="text-danger">{{$message}}</span>
                                @enderror -->
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="onboarding_date" id="datetimepicker" class="form-control" placeholder="OnBoarding date">
                                @error('onboarding_date')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="time_of_url_generation" class="form-control" placeholder="Time of url generation">
                                @error('time_of_url_generation')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div> -->
                       
                        <div class="row mb-3">
                            <div class="col">
                                <label>Status</label>
                                <input type="radio" name="status"  placeholder="Status" checked value="1">Active
                                <input type="radio" name="status" placeholder="Status" value="2">Dormant
                                @error('status')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label>User Type</label>
                                <input type="radio" name="apic_user_type" checked value="Basic">Basic
                                <input type="radio" name="apic_user_type" value="Pro">Pro
                                <input type="radio" name="apic_user_type" value="Premium">Premium
                                @error('apic_user_type')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-grid">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>