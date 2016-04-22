//
//  March.m
//  MazuMarch
//
//  Created by OA Wu on 2016/4/21.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "March.h"

@implementation March


- (March *) initWithDictionary:(NSDictionary *)dictionary {
    self = [super init];
    if (self) {
        self.marchId = [dictionary objectForKey:@"id"];
        self.title = [dictionary objectForKey:@"t"];
        self.enable = [[dictionary objectForKey:@"e"] boolValue] ? @"開啟" : @"關閉";
    }
    return self;
}

@end
