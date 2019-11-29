<?php

namespace ProtoneMedia\LaravelPaddle\Events;

/**
 * Fired when a customer opts in to receive marketing communication from you.
 */
class NewAudienceMember extends Event
{
    // {
    //   "alert_id": "20068520",
    //   "alert_name": "new_audience_member",
    //   "created_at": "2019-11-27 23:45:26",
    //   "email": "pascal@protone.media",
    //   "event_time": "2019-11-29 12:04:54",
    //   "marketing_consent": "0",
    //   "products": "577050,577051,577044",
    //   "source": "Checkout",
    //   "subscribed": "0",
    //   "user_id": "12935642"
    // }
}
