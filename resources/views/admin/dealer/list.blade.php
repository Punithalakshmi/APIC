@php
    use Carbon\Carbon;
@endphp
<x-app-layout>
   
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <div class="row">
                       <div class="col-md-3">
                        <h1 class="mb-0">Dealer List</h1>
                        </div>
                        <div class="col-md-3">
                       
                        </div>
                        <div class="col-md-3">
                      
                        </div>
                        <div class="col-md-3">
                        <a href="{{ route('admin/dealers/create') }}" class="btn btn-primary">Add Dealer</a>
                      </div>
                    </div>
                    <br />
                    <br/>
                    <div class="d-flex align-items-center justify-content-between">
                   
                        <form method="GET" action="{{ route('admin/dealers') }}">
                        <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="Search by Name" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="email" class="form-control" placeholder="Email" value="{{ request('email') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="status" class="form-control" placeholder="Status" value="{{ request('status') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="apic_user_type" class="form-control" placeholder="Apic User Type" value="{{ request('apic_user_type') }}">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
</div>
                        </form>
                        <br>
                    </div>
                    <hr />
                    @if(Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>
                    @endif
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                               <!-- <th>Dealer ID</th>-->
                                <th>Name</th>
                             <!--   <th>Email</th>-->
                                <th>Appuid</th>
                                <th>Current Url</th>
                                <th>Generation Time</th>
                                <th>Status</th>
                                <th>User Type</th>
                                <th>Is Token Generated</th>
                                <th>OnBoarding Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dealers as $dealer)
				                  @php
                                    $createdAt = Carbon::parse($dealer->time_of_url_generation);
                                  @endphp
                            <tr>
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <!--<td class="align-middle">{{ $dealer->dealer_id }}</td>-->
                                <td class="align-middle">{{ $dealer->name }}</td>
                                <!--<td class="align-middle">{{ $dealer->email }}</td>-->
                                <td class="align-middle">{{ $dealer->appuid }}</td>
                                <td class="align-middle"><a href="{{ $dealer->current_url }}" target="_blank">{{ substr($dealer->current_url, 0, 15) }}</a></td>
                                <td class="align-middle">{{ $dealer->time_of_url_generation }}</td>
                                <td class="align-middle">@if($dealer->status == 1) Active @else Dormant @endif</td>
                                <td class="align-middle">{{ $dealer->apic_user_type }}</td>
                                <td class="align-middle">
                                    @if($dealer->is_token_generated == 'Yes')
                                    <p class="text-primary">Success</p>
                                    @else
                                    <p>Not Yet</p>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $dealer->onboarding_date }}</td>
                                <td class="align-middle">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('admin/dealers/edit', ['id'=>$dealer->id]) }}" type="button" class="btn btn-secondary">Edit</a>
                                        <a href="{{ route('admin/dealers/delete', ['id'=>$dealer->id]) }}" onclick="return confirm('Are you sure you want to delete this dealer?');" type="button" class="btn btn-danger">Delete</a>
                                        <a href="{{ route('admin/dealers/upgrade', ['id'=>$dealer->id]) }}" onclick="return confirm('Are you sure you want to upgrade this dealer?');" type="button" class="btn btn-primary">Upgrade</a>
                                          @if($dealer->token == '') 
                                           <a href="{{ route('admin/api/register', ['id'=>$dealer->id]) }}" type="button" class="btn btn-primary">Register</a>
                                          @endif  
                                            @if($createdAt->lt(Carbon::now()->subDays(7)))
                                            <a href="{{ route('admin/api/refreshToken', ['id'=>$dealer->id]) }}" type="button" class="btn btn-primary">Refresh Token</a>
                                            @else
                                                
                                            @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="5">Dealer not found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                  <!-- Pagination Links -->
                {{ $dealers->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
