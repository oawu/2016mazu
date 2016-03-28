//
//  Message.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/28.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "Message.h"

@implementation Message

- (Message *) initWithDictionary:(NSDictionary *)dictionary {
    self = [super init];
    if (self) {
        self.id = [dictionary objectForKey:@"d"];
        self.content = [dictionary objectForKey:@"m"];
        self.time = [dictionary objectForKey:@"t"];
        self.ip = [dictionary objectForKey:@"i"];
        self.isAdmin = [[dictionary objectForKey:@"a"] boolValue];
//        NSLog(@"%@", [[dictionary objectForKey:@"a"] boolValue]);
    }
    return self;
}
@end
