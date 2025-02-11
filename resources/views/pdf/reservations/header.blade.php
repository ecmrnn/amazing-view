<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    html {
        -webkit-print-color-adjust: white;
    }
    .logo {
        width: 150px;
        aspect-ratio: 1/1;
    }
    header {
        width: 100%;
        font-size: 14px;
        padding: 48px;
        padding-top: 24px;
        color: #27272a;
        display: flex;
        justify-content: space-between;
    }
    #resortName,
    #reservationId
     {
        font-size: 18px;
        font-weight: 600;
    }
    #reservationId {
        color: #3B82F6;
    }
</style>

<header>
    <div>
        <p id="resortName">Amazing View Mountain Resort</p>
        <p>Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
    </div>

    <div style="text-align: right;">
        <p id="reservationId">{{ $reservation->rid }}</p>
        <p>Reservation ID</p>
    </div>
</header>