//
//  Header.h
//  Maps
//
//  Created by OA Wu on 2015/12/24.
//  Copyright © 2015年 OA Wu. All rights reserved.
//

#ifndef Header_h
#define Header_h

#define UPLOAD_PATHS_LIMIT 20
#define MARCH_ID 1
#define DEV YES

#define LOAD_MESSAGE_API_URL @"http://pic.mazu.ioa.tw/api/march/messages.json"
#define SEND_MESSAGE_API_URL @"http://mazu.ioa.tw/api/march_messages/"

#define LOAD_PATHS_API_URL @"http://pic.mazu.ioa.tw/api/march/%d/paths.json"
#define LOAD_GPS_API_URL @"http://pic.mazu.ioa.tw/api/march/gps.json"

#define CLEAN_API_URL @"http://mazu.ioa.tw/api/clean/"

#define LAST_API_URL @"http://mazu.ioa.tw/api/march/%@/paths/last"
#define BLACK_LIST_API_URL @"http://mazu.ioa.tw/api/march_message_blacklists"
#define CREATE_BLACK_API_URL @"http://mazu.ioa.tw/api/march_message_blacklists"
#define DELETE_BLACK_API_URL @"http://mazu.ioa.tw/api/march_message_blacklists/%d"

#define LOAD_MARCHES_API_URL @"http://mazu.ioa.tw/api/marches"
#define PUT_MARCH_API_URL @"http://mazu.ioa.tw/api/marches/%@"
#define GET_SETTING_API_URL @"http://mazu.ioa.tw/api/settings/1"
#define PUT_SETTING_API_URL @"http://mazu.ioa.tw/api/settings/1"
#define LOAD_PATH_API_URL @"http://pic.mazu.ioa.tw/api/path/"

#define USER_ID @"1"
#define LOAD_MESSAGE_TIMER 10 //sec
#define LOAD_PATH_TIMER 30 //sec

#define USER_DEFAULTS [NSUserDefaults standardUserDefaults]

#endif /* Header_h */
