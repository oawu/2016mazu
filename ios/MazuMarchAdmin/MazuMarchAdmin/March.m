//
//  March.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/4/22.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "March.h"

@implementation March

- (March *) initWithDictionary:(NSDictionary *)dictionary {
    self = [super init];
    if (self) {
        self.marchId = [dictionary objectForKey:@"id"];
        self.title = [dictionary objectForKey:@"t"];
        self.battery = [[dictionary objectForKey:@"b"] doubleValue];
        self.isEnable = [[dictionary objectForKey:@"e"] boolValue];
    }
    return self;
}
@end
