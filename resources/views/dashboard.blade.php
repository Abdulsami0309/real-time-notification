<x-app-layout>


    <div class="modal fade" id="message-confirm-popup" tabindex="-1">
        <form id="message-delete-form" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Message Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="message-area">Modal body text goes here.</p>
                        <input type="hidden" id="message-id" name="message_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes, Delete It.</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-sm-3 col-md-5 col-lg-3">
                <div class="card">
                    <div class="card-header">
                        All Users
                    </div>
                    <div class="card-body">
                        <ul class="list-group all-users">
                            @forelse($users as $user)
                                <li data-id="{{ $user->id }}" class="list-group-item single-user"><img
                                        width="50" src="{{ asset('dummy-user.png') }}" alt="User Image">
                                    <span id="user-{{$user->id}}-name" >{{ $user->name }}
                                        <span id="user-{{ $user->id }}-status" class="user-status-dot" ></span>
                                    </span> </li>
                            @empty
                                <li class="list-group-item">No User Found !</li>
                            @endforelse

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 col-md-7 col-lg-9" id="chat-with-user-area">
                <div class="card">
                    <div class="card-header" id="set-username">
                        
                    </div>
                    <div class="card-body">
                        <div id="chat-area">
                            <div id="chat-messages">

                            </div>

                        </div>

                    </div>
                </div>
                <form id="send-message-form">
                    <div class="send-message-area mt-4">
                        <div class="row align-items-center">
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <textarea id="chat-input" required class="form-control" placeholder="Enter Your Message"></textarea>
                                </div>

                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
