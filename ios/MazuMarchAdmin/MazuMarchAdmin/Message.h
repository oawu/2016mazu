//
//  Message.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/28.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Message : NSObject

@property NSString *id, *time, *content, *ip;
@property BOOL isAdmin;

- (Message *) initWithDictionary:(NSDictionary *)dictionary;
@end
