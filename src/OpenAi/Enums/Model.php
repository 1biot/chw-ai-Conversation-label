<?php

namespace OpenAi\Enums;

enum Model: string
{
    case GPT_4O = 'gpt-4o';
    case GPT_4O_Mini = 'gpt-4o-mini';
    case GPT_4_Turbo = 'gpt-4-turbo';
    case GPT_4 = 'gpt-4';
    case GPT_3_5_turbo = 'gpt-3.5-turbo';
}
