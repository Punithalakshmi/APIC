<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-0">Update Dealer</h1>
                    <hr />
                    @if (session()->has('error'))
                    <div>
                        {{session('error')}}
                    </div>
                    @endif
                    <p><a href="{{ route('admin/dealers') }}" class="btn btn-primary">Go Back</a></p>
 
                    <form action="/admin/dealers/edit/{{$dealers->id}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="dealer_id" class="form-control" placeholder="Dealer ID" value="{{$dealers->dealer_id}}">
                                @error('dealer_id')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{$dealers->name}}">
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="email" class="form-control" placeholder="Email" value="{{$dealers->email}}">
                                @error('email')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="appuid" class="form-control" placeholder="App Uid" value="{{$dealers->appuid}}">
                                @error('appuid')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="current_url" class="form-control" placeholder="Current URL" value="{{$dealers->current_url}}">
                                @error('current_url')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="onboarding_date" id="datetimepicker" class="form-control" placeholder="OnBoarding date" value="{{$dealers->onboarding_date}}">
                                @error('onboarding_date')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="row mb-3">
                            <div class="col">
                                <input type="radio" name="status"  placeholder="Status" value="1" {{ old('status', $dealers->status ?? '') == 1 ? 'checked' : '' }}>Active
                                <input type="radio" name="status" placeholder="Status" value="2" {{ old('status', $dealers->status ?? '') == 2 ? 'checked' : '' }}>Dormant
                                @error('status')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-grid">
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>