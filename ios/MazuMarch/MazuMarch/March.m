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
        self.marchId = [dictionary objectForKey:@"i"];
        self.title = [dictionary objectForKey:@"t"];
    }
    return self;
}

@end
