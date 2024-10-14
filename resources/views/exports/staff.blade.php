<table>
    <thead>
    <tr>
        <th>ФИО</th>
        <th>СНИЛС</th>
        <th>Должность</th>
        <th>Срок действия ЭЦП</th>
    </tr>
    </thead>
    <tbody>
    @foreach($staffs as $staff)
        <tr>
            <td>{{ $staff->full_name }}</td>
            <td style="text-align: right">{{ $staff->snils }}</td>
            <td>{{ $staff->job_title }}</td>
            <td style="text-align: right">{{ \Illuminate\Support\Carbon::createFromTimestampMs($staff->certification->valid_to)->format('d.m.Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
