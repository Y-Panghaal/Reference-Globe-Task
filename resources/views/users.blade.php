@foreach ($users as $user)
    <tr>
        <td>
            {{ $user->id }}
        </td>
        <td>
            <img class="mr-5" src="{{ \Storage::disk('public')->url($user->profile_picture) }}" alt="" style="max-height:40px;border:1px solid black;">
            {{ $user->name }}
        </td>
        <td>
            {{ $user->mobile }}
        </td>
        <td>
            {{ $user->email }}
        </td>
        <td style="text-align:left;">
            <pre>{{ $user->address }}</pre>
        </td>
        <td>
            @if((int)$user->gender === 1)
                Female
            @else
                Male
            @endif
        </td>
        <td>
            {{ Carbon\Carbon::parse($user->date_of_birth)->toFormattedDateString() }}
        </td>
        <td>
            <img src="{{ \Storage::disk('public')->url($user->signature) }}" alt="" style="max-height:40px;border:1px solid black;">
        </td>
        <td>
            @if((int)$user->status === 1)
                Approved
            @else
                Pending Approval
                @if((int)\Auth::user()->role)
                @endif
                <a href="{{ route('user.approve', ['id' => $user->id]) }}">Approve</a>
            @endif
        </td>
        <td>
            @switch((int)\Auth::user()->role)
                @case(0)
                    <a class="btn btn-primary edit-user" href="{{ route('user.edit.view', ['id' => $user->id]) }}">Edit</a>
                    @break
                @case(2)
                    <a class="btn btn-primary edit-user" href="{{ route('user.edit.view', ['id' => $user->id]) }}">Edit</a>
                @case(1)
                    <a class="btn btn-primary delete-user" href="{{ route('user.delete', ['id' => $user->id]) }}">Delete</a>
                    @break
                @default
            @endswitch
        </td>
    </tr>
@endforeach