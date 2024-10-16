@extends('layouts.admin')
@section('styles')
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        Send a mail to all users
    </div>

    <div class="card-body">
        <div class="text-center">
            <button class="btn btn-primary btn-lg" onclick="sync()">
                Send
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
@parent
<script>
    function sync() {


        Swal.fire({
            title: "Please Confirm if you want to send a mail to all users",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#36c6d3',
            cancelButtonColor: '#d33',
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.users.syncUserCompletedMail') }}",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success == false) {
                            console.log(response);
                        } else {
                            console.log(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if needed
                        console.error(xhr.responseText);
                    }
                });
            } else if (result.dismiss == 'cancel') {
                console.log('cancel');
            }

        });

    }
</script>
@endsection