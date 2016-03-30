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
#define CLEAN_API_URL @"http://mazu.ioa.tw/api/clean/"
#define LAST_API_URL @"http://mazu.ioa.tw/api/march/%d/paths/last"
#define BLACK_LIST_API_URL @"http://mazu.ioa.tw/api/march_message_blacklists"
#define DELETE_BLACK_API_URL @"http://mazu.ioa.tw/api/march_message_blacklists/%d"


#define USER_ID @"1"
#define LOAD_MESSAGE_TIMER 5 //sec
#define LOAD_PATH_TIMER 30 //sec

#define USER_DEFAULTS [NSUserDefaults standardUserDefaults]

#endif /* Header_h */
