<x-app-layout>
   
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="mb-0">Dealer List</h1>
                        <a href="{{ route('admin/dealers/create') }}" class="btn btn-primary">Add Dealer</a>
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
                                <th>Dealer ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Appuid</th>
                                <th>Current Url</th>
                                <th>Time of Url Generation</th>
                                <th>Status</th>

                                <th>OnBoarding Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dealers as $dealer)
                            <tr>
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">{{ $dealer->dealer_id }}</td>
                                <td class="align-middle">{{ $dealer->name }}</td>
                                <td class="align-middle">{{ $dealer->email }}</td>
                                <td class="align-middle">{{ $dealer->appuid }}</td>
                                <td class="align-middle">{{ $dealer->current_url }}</td>
                                <td class="align-middle">{{ $dealer->time_of_url_generation }}</td>
                                <td class="align-middle">@if($dealer->status == 1) Active @else Dormant @endif</td>
                                <td class="align-middle">{{ $dealer->onboarding_date }}</td>
                                <td class="align-middle">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('admin/dealers/edit', ['id'=>$dealer->id]) }}" type="button" class="btn btn-secondary">Edit</a>
                                        <a href="{{ route('admin/dealers/delete', ['id'=>$dealer->id]) }}" type="button" class="btn btn-danger">Delete</a>
                                        <a href="{{ route('admin/api/register', ['id'=>$dealer->id]) }}" type="button" class="btn btn-primary">Register Coohom</a>
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>