<ul id="userList">
    @foreach($users as $user)
        <li>
            <div class="user" data-id="{{$user->id}}" data-name="{{ $user->name }}" data-phone="{{ $user->phone }}">
                {{ __($user->name ?? '' ) }} / {{ $user->phone ?? '' }}
            </div>
        </li>
    @endforeach
</ul>
