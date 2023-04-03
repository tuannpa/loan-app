<?php

enum State: string {
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case PAID = 'paid';
}
