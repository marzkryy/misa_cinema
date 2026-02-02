$Action = New-ScheduledTaskAction -Execute "c:\laragon\www\misacinema\auto_cancel.bat"
$Trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 10)
$Settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable
Register-ScheduledTask -Action $Action -Trigger $Trigger -Settings $Settings -TaskName "MisaCinemaAutoCancel" -Description "Auto cancels pending bookings older than 15 mins" -Force

Write-Host "Task 'MisaCinemaAutoCancel' created successfully. It will run every 10 minutes."
